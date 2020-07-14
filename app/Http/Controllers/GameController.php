<?php

namespace App\Http\Controllers;

use App\Events\GameChanged;
use App\Events\GameMembersCountChanged;
use App\Events\GameTeamsChanged;
use App\Events\UserChanged;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\GameCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function becomeMember(Game $game)
    {
        if (now()->diffInMinutes($game->planned_at, false) < 1) {
            return [
                'error' => 'to_late'
            ];
        }

        $user = Auth::user();

        $current = $game->members()->lockForUpdate()->count();
        if ($current >= $game->max_players * $game->mul) {
            return [
                'error' => 'full'
            ];
        }

        if ($game->price > $user->balance) {
            return [
                'error' => 'no_money'
            ];
        }

        if ($user->relatedGames()->wherePivot('game_id', $game->id)
            ->exists()) {
            return [
                'error' => 'full'
            ];
        }


        $game->members()->syncWithoutDetaching([$user->id]);

        // Если меньше часа с момента регистрации, даем макс килл
        if (now()->subHour() <= $user->created_at) {
            $game->members()->updateExistingPivot($user->id, ['bonus' => 1]);
        }

        /** @var Game $newGame */
        $newGame = Game::withCount('members')->where('id', $game->id)->first();
        $newGame->isMember = true;
        $user->decrement('balance', $game->price);

        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$game->price,
            'type' => Transaction::TYPE_GAME_PAYMENT,
            'status' => Transaction::STATUS_NORMAL,
            'comment' => '',
        ]);

        event(new GameMembersCountChanged($newGame->id, $newGame->members_count));
        event(new UserChanged($user));

        // Запись менее 10 минут, сразу отправляем данные для доступа
        if (now()->diffInMinutes($game->planned_at, false) < 10) {
            $userTeam = '';
            $gameIndex = 0;
            if ($newGame->mul == 2) {
                $count0 = $newGame->members->filter(function ($member) {
                    return $member->pivot->gi == 0;
                })->count();

                $count1 = $newGame->members->filter(function ($member) {
                    return $member->pivot->gi == 1;
                })->count();

                $gameIndex = 0;
                if ($count0 == 100) {
                    $gameIndex = 1;
                } elseif ($count1 == 100) {
                    $gameIndex = 0;
                } elseif ($count0 < $count1) {
                    $gameIndex = 0;
                } else {
                    $gameIndex = 1;
                }

                $newGame->members()->updateExistingPivot($user->id, [
                    'gi' => $gameIndex
                ]);
            }

            if ($game->type !== Game::TYPE_SOLO) {
                $teams = $newGame->getTeamsArray([])[$gameIndex];
                $members = [];

                foreach ($newGame->members as $member) {
                    if ($newGame->mul == 2) {
                        if ($member->pivot->team && $member->pivot->gi == $gameIndex) {
                            $teams[$member->pivot->team][] = $member;
                        }
                    } else {
                        if ($member->pivot->team) {
                            $teams[$member->pivot->team][] = $member;
//                    $members[$member->id] = $member->pivot->team;
                        }
                    }
                }

                $currentTeam = 1;

                debug($teams);

                // 2. Заполняем команды участниками
                foreach ($newGame->members as $member) {
                    if (!$member->pivot->team) {
                        while (count($teams['team ' . $currentTeam]) >= $newGame->getTeamSize()) {
                            $currentTeam++;
                        }
                        $teams['team ' . $currentTeam][] = $member;
//                    $members[$member->id] = 'team ' . $currentTeam;
                        $newGame->members()->updateExistingPivot($member->id, [
                            'team' => 'team ' . $currentTeam
                        ]);

                        if ($member->id == $user->id) {
                            $userTeam = $currentTeam;
                        }
                    }
                }
            }

            $newGame = Game::where('id', $newGame->id)
                ->withCount(['members',
                    'members as isMember' => function ($query) use ($user) {
                        if ($user) {
                            $query->where('id', $user->id);
                        } else {
                            $query->where('id', 0);
                        }
                    }])
                ->with(['members' => function ($query) use ($user) {
                    if ($user) {
                        $query->where('id', $user->id);
                    } else {
                        $query->where('id', 0);
                    }
                }])
                ->first();

            // Уведомляем участников
            if ($newGame->mul == 2) {
                $gameAr = $newGame->toArray();
                if ($gameIndex == 1) {
                    $gameAr['login'] = $gameAr['login2'];
                    $gameAr['password'] = $gameAr['password2'];
                }
                $user->notify(new GameCredentials($gameAr, $userTeam));
            } else {
                $user->notify(new GameCredentials($newGame->toArray(), $userTeam));
            }

        }

        return [
            'error' => 'no',
            'game' => $newGame
        ];
    }

    public function leaveGame(Game $game)
    {
        $user = Auth::user();

        if ($game->members()
            ->where('id', $user->id)
            ->lockForUpdate()
            ->first()) {

            $game->members()->detach($user->id);

            $newGame = Game::withCount('members')->where('id', $game->id)->first();
            $newGame->isMember = false;

            $user->increment('balance', $game->price);

            Transaction::create([
                'user_id' => $user->id,
                'amount' => $game->price,
                'type' => Transaction::TYPE_GAME_RETURN,
                'status' => Transaction::STATUS_NORMAL,
                'comment' => '',
            ]);

            event(new GameMembersCountChanged($newGame->id, $newGame->members_count));
            event(new UserChanged($user));

            return [
                'error' => 'no',
                'game' => $newGame
            ];
        } else {
            $newGame = Game::withCount('members')->where('id', $game->id)->first();
            $newGame->isMember = false;

            return [
                'error' => 'already_leave',
                'game' => $newGame
            ];
        }
    }

    public function getTeams(Game $game)
    {
        $teams = $game->getTeamsArray();

        foreach ($game->members as $member) {
            if ($member->pivot->team) {
                if (!isset($teams[$member->pivot->gi])) {
                    $teams[$member->pivot->gi] = [];
                }

                if (!isset($teams[$member->pivot->gi][$member->pivot->team])) {
                    $teams[$member->pivot->gi][$member->pivot->team] = 0;
                }

                $teams[$member->pivot->gi][$member->pivot->team]++;
            }
        }

        return $teams;
    }

    public function setMode(Game $game, $isSingle, $team, $groupIndex)
    {
        if ($game->planned_at < now()) {
            return [
                'error' => __('game.errors.game_started')
            ];
        }

        if ($game->planned_at->diffInMinutes(now(), true) <= 10) {
            return [
                'error' => __('game.errors.10minutes')
            ];
        }

        if ($game->type == Game::TYPE_SOLO) {
            return [];
        }

        /** @var User $user */
        $user = Auth::user();

        if ($isSingle) {
            $game->members()->updateExistingPivot($user->id, ['team' => null, 'gi' => null]);

        } else {
            if (!is_numeric($team)) {
                return [
                    'error' => __('game.errors.wrong_team'),
                    'not_number' => true
                ];
            }

            $teamName = 'team ' . $team;

            /** @var Collection $members */
            $members = $game->members()
                ->wherePivot('team', $teamName)
                ->wherePivot('gi', $groupIndex)
                ->lockForUpdate()
                ->get();

            if ($members->contains('id', $user->id)) {
                return ['error' => __('game.errors.already_in_team'), 'already' => true];
            }

            if ((count($members)) >= $game->getTeamSize()) {
                return ['error' => __('game.errors.team_full'), 'full' => true];
            }

            $game->members()->updateExistingPivot($user->id, ['team' => $teamName, 'gi' => $groupIndex]);
        }

        $teams = $this->getTeams($game);

        event(new GameTeamsChanged($game->id, $teams));

        return [
            'error' => false,
            'teams' => $teams
        ];
    }

    public function getMode(Game $game)
    {
        return $game->members()->where('id', Auth::user()->id)->first()->pivot;
    }

    public function getGames()
    {
        $user = Auth::user();
        $gamesIds = collect();

        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_PUBG)
            ->notKing()
            ->take(7)
            ->pluck('id'));


        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_CALL_OF_DUTY)
            ->notKing()
            ->take(7)
            ->pluck('id'));

        $gamesIds = $gamesIds->merge(Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
            ->orderBy('planned_at', 'asc')
            ->where('game_code', Game::GAME_FREEFIRE)
            ->notKing()
            ->take(7)
            ->pluck('id'));

        return Game::whereIn('id', $gamesIds)
            ->orderBy('planned_at', 'asc')
            ->withCount(['members',
                'members as isMember' => function ($query) use ($user) {
                    if ($user) {
                        $query->where('id', $user->id);
                    } else {
                        $query->where('id', 0);
                    }
                }])
            ->with(['members' => function ($query) use ($user) {
                if ($user) {
                    $query->where('id', $user->id);
                } else {
                    $query->where('id', 0);
                }
            }])
            ->get();
    }
}
