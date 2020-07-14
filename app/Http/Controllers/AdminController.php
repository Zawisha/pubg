<?php

namespace App\Http\Controllers;

use App\Events\StatUpdated;
use App\Events\UserChanged;
use App\Http\Classes\ImageParser;
use App\Jobs\ExportStatAsExcel;
use App\Jobs\SendGameResultsJob;
use App\Models\Admin;
use App\Models\Game;
use App\Models\Rank;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AdminGameResults;
use App\Notifications\CashbackIncome;
use App\Notifications\GameEnded;
use App\Notifications\GameResults;
use App\Notifications\NewRank;
use App\Notifications\Top1Fee;
use App\Notifications\Top2Fee;
use App\Notifications\Top3Fee;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
    }

    public function getGameMembers(Game $game, Request $request)
    {
        $multiple = $request->get('multiple', 1);
        $index = $request->get('index', null);

        if ($index < 0) {
            $index = null;
        }

        if ($multiple == 1) {
            $game->load('members');
        } else {
            $game->members = $game->members()->wherePivot('gi', $index)->get();
        }

//        $game->members->orderBy('pivot.team');
//        $members = $game->members;
//        $members = $members->sort(function ($a, $b) {
//            if ($a['pivot']['team'] == $b['pivot']['team']) {
//
//            }
//        });
        return $game;
    }

    public function setGameMembers(Request $request)
    {
        $game = $request->get('game');

        debug($game);

        /** @var Game $dbGame */
        $dbGame = Game::find($game['id']);

        foreach ($game['members'] as $member) {
            $pivotData = [
                'kills' => $member['pivot']['kills'],
                'visit' => $member['pivot']['visit']
            ];

            if (isset($member['pivot']['color'])) {
                $pivotData['color'] = $member['pivot']['color'];
            }

            if (isset($member['pivot']['kills1'])) {
                $pivotData['kills1'] = $member['pivot']['kills1'];
            }
            if (isset($member['pivot']['kills2'])) {
                $pivotData['kills2'] = $member['pivot']['kills2'];
            }
            if (isset($member['pivot']['kills3'])) {
                $pivotData['kills3'] = $member['pivot']['kills3'];
            }

            $dbGame->members()->updateExistingPivot($member['id'], $pivotData);
        }

        $dbGame->load('members');

        return $dbGame;
    }


    protected function calcReward(Game $dbGame, $killReward)
    {
        if ($dbGame->isDouble()) {
            return round($dbGame->price * $killReward / 100);
        }

        return ceil(round($dbGame->price * $killReward / 5, 5)) * 0.05;
    }

    /**
     * @param Request $request
     * @return Game
     */
    public function publishGameMembers(Request $request)
    {

        $game = $request->get('game');
        $totalPayed = 0;
        $gameIndex = $request->get('index', 0);

        debug($game);

        /** @var Game $dbGame */
        $dbGame = $this->setGameMembers($request);
        $dbGame = Game::find($game['id']);

        if ($dbGame->isDouble()) {
            if ($gameIndex == 0) {
                if ($dbGame->results_published) {
                    $dbGame->load('members');
                    return $dbGame;
                }
                $dbGame->results_published = true;
                $dbGame->save();
            }

            if ($gameIndex == 1) {
                if ($dbGame->results_published2) {
                    $dbGame->load('members');
                    return $dbGame;
                }
                $dbGame->results_published2 = true;
                $dbGame->save();
            }
        } else {

            if ($dbGame->results_published) {
                $dbGame->load('members');
                return $dbGame;
            }
            $dbGame->results_published = true;
            $dbGame->save();
        }

        $ranks = Rank::all();
        $ranks = $ranks->keyBy('id');

        $maxKillReward = $ranks->max(function ($rank) use ($dbGame) {
            if ($dbGame->isDouble()) {
                return $rank->kill_reward2;
            }

            return $rank->kill_reward;
        });

        $members = collect($game['members']);

        $members = $members->groupBy(function ($member) {
            return $member['pivot']['kills'];
        });


        $topKeys = $members->keys()->sort()->reverse()->values()->toArray();
        $tops = $members[max($topKeys)]->pluck('name')->toArray();
        /** @var Collection $topsIds */
        $topsIds = $members[max($topKeys)]->pluck('id');

//        $tops2 = collect();
        $tops2Ids = collect();

        if (isset($topKeys[1])) {
//            $tops2 = $members[$topKeys[1]]->pluck('name')->toArray();
            $tops2Ids = $members[$topKeys[1]]->pluck('id');
        }

//        $tops3 = collect();
        $tops3Ids = collect();

        if (isset($topKeys[2])) {
//            $tops3 = $members[$topKeys[2]]->pluck('name')->toArray();
            $tops3Ids = $members[$topKeys[2]]->pluck('id');
        }

        $winTable = collect($game['members']);
        $winTable = $winTable->sort(function ($a, $b) {
            if ($a['pivot']['kills'] == $b['pivot']['kills']) {
                return 0;
            }
            return ($a['pivot']['kills'] > $b['pivot']['kills']) ? -1 : 1;
        });

//        debug($winTable);

        $winTableSorted = [];

        foreach ($winTable as $key => $val) {
            if ($val['pivot']['visit']) {
                $winTableSorted[] = [
                    'name' => $val['name'],
                    'kills' => $val['pivot']['kills']
                ];
            }
        };

//        debug($winTableSorted);

        $date = $dbGame->planned_at;
        $date->setTimezone('Europe/Moscow');

        foreach ($game['members'] as $member) {
            $dbGame->members()->updateExistingPivot($member['id'], [
                'kills' => $member['pivot']['kills'] ? $member['pivot']['kills'] : 0,
                'visit' => $member['pivot']['visit']
            ]);

            if ($member['pivot']['visit']) {
                User::where('id', $member['id'])->update([
                    'kills' => DB::raw('kills + ' . $member['pivot']['kills']),
                    'games' => DB::raw('games + 1'),
                ]);

                User::where('id', $member['id'])->update([
                    'kd' => DB::raw('kills/games')
                ]);

                /** @var User $user */
                $user = User::find($member['id']);
                /** @var Rank $currentRank */
                $currentRank = $ranks[$user->rank_id];

                // Вычисляем сумму для зачисления
                $amount = $this->calcReward($dbGame,
                        $dbGame->isDouble()
                            ? $currentRank->kill_reward2
                            : $currentRank->kill_reward)
                    * $member['pivot']['kills'];

                // Если не действует индивидуальный игровой бонус
                // Если активны бонусные игры
                if ($member['pivot']['bonus'] == 0) {
                    if ($user->bonus_games > 0) {
                        $amount = $this->calcReward($dbGame, $maxKillReward)
                            * $member['pivot']['kills'];
                        // Уменьшаем количество бонусных игр
                        $user->decrement('bonus_games', 1);
                    }
                }

                // Если действует индивидуальный игровой бонус
                if ($member['pivot']['bonus'] != 0) {
                    $amount = $this->calcReward($dbGame, $maxKillReward)
                        * $member['pivot']['kills'];
                }

                if ($dbGame->use_max_kill) {
                    $amount = $this->calcReward($dbGame, $maxKillReward)
                        * $member['pivot']['kills'];
                }

                if ($dbGame->is_king) {
                    if ($dbGame->use_max_kill) {
                        $amount = $this->calcReward($dbGame, $maxKillReward / 3)
                            * $member['pivot']['kills'];
                    } else {
                        $amount = $this->calcReward($dbGame, $dbGame->isDouble()
                                ? $currentRank->kill_reward2 / 3
                                : $currentRank->kill_reward / 3)
                            * $member['pivot']['kills'];
                    }
                }

                // Уведомление о зачислении
                $user->notify(new GameEnded(
                    $amount,
                    $member['pivot']['kills'],
                    $tops,
                    $date,
                    $winTableSorted,
                    $user->bonus_used,
                    $dbGame->is_king
                ));

                if ($amount > 0) {
                    // Зачисляем на счет
                    $user->transactions()->create(
                        [
                            'amount' => $amount,
                            'type' => Transaction::TYPE_KILL,
                            'status' => Transaction::STATUS_NORMAL,
                            'comment' => '',
                        ]
                    );

                    $user->increment('balance', $amount);
                    $totalPayed += $amount;

                    try {
                        // Пользователь из топ1
                        if ($dbGame->top1_prize && $topsIds->contains($user->id)) {
                            $user->transactions()->create(
                                [
                                    'amount' => $dbGame->top1_prize,
                                    'type' => Transaction::TYPE_TOP1_PAYMENT,
                                    'status' => Transaction::STATUS_NORMAL,
                                    'comment' => '',
                                ]
                            );

                            $user->increment('balance', $dbGame->top1_prize);
                            $totalPayed += $dbGame->top1_prize;

                            $user->notify(new Top1Fee($dbGame));
                        }

                        // Пользователь из топ2
                        if ($dbGame->top2_prize && $tops2Ids->contains($user->id)) {
                            $user->transactions()->create(
                                [
                                    'amount' => $dbGame->top2_prize,
                                    'type' => Transaction::TYPE_TOP2_PAYMENT,
                                    'status' => Transaction::STATUS_NORMAL,
                                    'comment' => '',
                                ]
                            );

                            $user->increment('balance', $dbGame->top2_prize);
                            $totalPayed += $dbGame->top2_prize;

                            $user->notify(new Top2Fee($dbGame));
                        }

                        // Пользователь из топ3
                        if ($dbGame->top3_prize && $tops3Ids->contains($user->id)) {
                            $user->transactions()->create(
                                [
                                    'amount' => $dbGame->top3_prize,
                                    'type' => Transaction::TYPE_TOP3_PAYMENT,
                                    'status' => Transaction::STATUS_NORMAL,
                                    'comment' => '',
                                ]
                            );

                            $user->increment('balance', $dbGame->top3_prize);
                            $totalPayed += $dbGame->top3_prize;

                            $user->notify(new Top3Fee($dbGame));
                        }
                    } catch (\Throwable $ex) {
                        Log::debug($ex);
                    }
                }

                // Если на игру бонус не распространяется
                // Если 0 убийств и бонус еще не использовали
                if ($member['pivot']['bonus'] == 0) {
                    if (($member['pivot']['kills'] == 0) && !$user->bonus_used) {
                        $user->bonus_games = 3;
                        $user->bonus_used = true;
                        $user->save();
                    }
                }

                // Уведомляем пользователя об изменении баланса
                event(new UserChanged($user));

                // Смотрим, не надо ли закинут кэшбек
                if ($user->referral_id && $user->referral && ($amount > 0)) {
                    $addCashback = true;

                    if ($user->referral->cbl) {
                        $addCashback = random_int(0, 100) < $user->referral->cbl;

                        Log::debug('User ' . $user->referral->name . '(' . $user->referral->cbl . ') cashback limit: '
                            . ($addCashback ? 'no' : 'yes'));
                    }

                    if ($addCashback) {
                        $referralCashback = $ranks[$user->referral->rank_id]->cashback / 100;

                        $cashbackAmount = round($amount * $referralCashback, 2); // срезать тут

                        // Транзакция
                        $user->referral->transactions()->create([
                            'amount' => $cashbackAmount,
                            'type' => Transaction::TYPE_GAME_FEE,
                            'status' => Transaction::STATUS_NORMAL,
                            'comment' => 'user_id:' . $user->id,
                        ]);

                        // Начисляем кэшбек
                        $user->referral->increment('balance', $cashbackAmount);
                        $totalPayed += $cashbackAmount;

//                    // Отправляем уведомление о зачислении баланса
                        $user->referral->notify(new CashbackIncome(
                            CashbackIncome::REASON_GAME,
                            $user->name,
                            $user->id,
                            $cashbackAmount));
                        // Уведомляем пользователя об изменении баланса
                        event(new UserChanged($user->referral));
                    }
                }


                // Проверяем, не надо ли повысить ранг пользователя
                if (isset($ranks[$user->rank_id + 1])) {
                    /** @var Rank $rank */
                    $rank = $ranks[$user->rank_id + 1];
                    if ($user->kills >= $rank->rq_kills
                        && $user->games >= $rank->rq_battles) {
                        // Итак, мы достигли новый уровень
                        $user->rank_id = $rank->id;
                        $user->save();

                        // Разослать уведомления!!
                        $user->notify(new NewRank($rank));
                    }
                }
            } else {

            }
        }

        if ($dbGame->isDouble()) {
            $dbGame->members = $dbGame->members()->wherePivot('gi', $gameIndex)->get();
        } else {
            $dbGame->load('members');
        }


        $evt = new GameEnded(
            0,
            0,
            [''],
            $date,
            $winTableSorted,
            false,
            $dbGame->is_king,
            $dbGame->game_code
        );

        $imageName = $evt->preapareImage((object)['name' => '', 'id' => 'game-' . $dbGame->id . '-' . $gameIndex . '-results']);
        $dbGame->increment('total_payed', $totalPayed);

        SendGameResultsJob::dispatch($dbGame, $imageName);
        try {
            /** @var Admin $admin */
            foreach (Admin::all() as $admin) {
                if ($admin->telegram_id) {
                    $admin->notify(new AdminGameResults($imageName, $dbGame->is_king));
                }
            }
        } catch (\Throwable $er) {

        }

        event(new StatUpdated());
        //(new GameResults($this->imageName, $this->game->is_king)

        return $dbGame;
    }

    public function fillTeams(Request $request)
    {
        if (!$game = $request->get('game')) {
            abort(422);
        }

        $game = Game::find($game['id']);
        // Назначаем команды
        // 1. Формируем массив в котором уже заполненные участники помечены
        // и для участников запоминаем в каких они командах
        $teams = $game->getTeamsArray([])[0];
        $members = [];

        foreach ($game->members as $member) {
            if ($member->pivot->team) {
                $teams[$member->pivot->team][] = $member;
//                    $members[$member->id] = $member->pivot->team;
            }
        }

        $currentTeam = 1;

        // 2. Заполняем команды участниками
        foreach ($game->members as $member) {
            if (!$member->pivot->team) {
                while (count($teams['team ' . $currentTeam]) >= $game->getTeamSize()) {
                    $currentTeam++;
                }
                $teams['team ' . $currentTeam][] = $member;
//                    $members[$member->id] = 'team ' . $currentTeam;
                $game->members()->updateExistingPivot($member->id, [
                    'team' => 'team ' . $currentTeam
                ]);
            }
        }

        $dbGame = Game::find($game->id);
        $dbGame->load('members');

        return $dbGame;
    }

    public function uploadScreens(Request $request)
    {
        $file = $request->file('file');
        $result = ImageParser::parseAllPresets($file->getPathname());
        return ['status' => 'ok', 'result' => $result];
    }

    public function processParsedData(Request $request)
    {
        $data = collect($request->get('data', []));
        $gameId = $request->get('gameId', 0);

        $result = ImageParser::processParseResults($data, $gameId);

        return ['status' => 'ok', 'data' => $result];
    }

    public function sendStatToEmail(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        ExportStatAsExcel::dispatch(storage_path('stat_reports'), $data['email']);

        return ['errors' => false];
    }
}
