<?php

namespace App\Jobs;

use App\Events\GamesChanged;
use App\Models\Admin;
use App\Models\Game;
use App\Notifications\AdminRemainCredentials;
use App\Notifications\GameCredentials;
use App\Notifications\GameRemaind;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GameReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $timeout = 900;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    protected function findEmptyTeam($teams, $count = 2)
    {
        if ($count == 2) {
            for ($i = 1; $i <= 50; $i++) {
                if (!isset($teams['team ' . $i])) {
                    return 'team ' . $i;
                }
            }
        }

        if ($count == 1) {
            for ($i = 1; $i <= 100; $i++) {
                if (!isset($teams['team ' . $i])) {
                    return 'team ' . $i;
                }
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        Log::debug('Starting reminder job');
        // Игры которые должны начаться через 1 час
        $games = Game::where('planned_at', '>=', now()->addHour()->startOfMinute())
            ->where('planned_at', '<=', now()->addHour()->endOfMinute())
            ->get();

        foreach ($games as $game) {
            \Notification::send($game->members, new GameRemaind($game));
        }

        // Игры которые должны начаться через 30 минут
        $games = Game::where('planned_at', '>=', now()->addMinutes(30)->startOfMinute())
            ->where('planned_at', '<=', now()->addMinutes(30)->endOfMinute())
            ->get();

//        $admins = Admin::where('role', '<>', 'super_admin')->get();
        $admins = Admin::all();

        foreach ($games as $game) {
            // Пинаем юзеров
            \Notification::send($game->members, new GameRemaind($game, false));

            // Пинаем админов, если не заполнен логин и пароль
            if ($game->password == '' || $game->login == '') {
//                \Notification::send($admins, new AdminRemainCredentials($game));
            }
        }

        // Игры которые должны начаться через 20 минут
        $games = Game::where('planned_at', '>=', now()->addMinutes(20)->startOfMinute())
            ->where('planned_at', '<=', now()->addMinutes(20)->endOfMinute())
            ->get();

        foreach ($games as $game) {
            // Пароль и логин все-еще не указан! Пинаем админов
            if ($game->password == '' || $game->login == '') {
//                \Notification::send($admins, new AdminRemainCredentials($game));
            }
        }

        // Игры которые должны начаться через 10 минут
        $games = Game::where('planned_at', '>=', now()->addMinutes(10)->startOfMinute())
            ->where('planned_at', '<=', now()->addMinutes(10)->endOfMinute())
            ->get();

//        $games = Game::where('id', 295)->get();

        /** @var Game $game */
        foreach ($games as $game) {
            if ($game->mul < 2) {
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

                // Уведомляем участников
                foreach ($game->members()->get() as $member) {
                    $member->notify(new GameCredentials($game->toArray(), str_replace('team ', '', $member->pivot->team)));
                }
            } elseif ($game->mul == 2) {
                // Single game
                $team1 = 1;
                $team2 = 1;

                if ($game->type == Game::TYPE_SOLO) {
                    $later = collect();

                    foreach ($game->members()->orderBy('kd', 'asc')->get() as $idx => $member) {
                        if ($member->kd >= 3) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . $team2++,
                                'gi' => 1
                            ]);
                        } elseif ($member->kd <= 0.0001) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . $team1++,
                                'gi' => 0
                            ]);
                        } else {
                            $later->push($member);
                        }

                        $later = $later->sortBy('kd');

                        while (!$later->isEmpty()) {
                            if ($team1 <= 100) {
                                $member = $later->shift();
                                $game->members()->updateExistingPivot($member->id, [
                                    'team' => 'team ' . $team1++,
                                    'gi' => 0
                                ]);
                            }

                            if (($team2 <= 100) && !$later->isEmpty()) {
                                $member = $later->pop();
                                $game->members()->updateExistingPivot($member->id, [
                                    'team' => 'team ' . $team2++,
                                    'gi' => 1
                                ]);
                            }
                        }
                    }

                    $teams = [0 => [], 1 => []];
                    foreach ($game->members()->get() as $member) {
                        if (!isset($teams[$member->pivot->gi][$member->pivot->team])) {
                            $teams[$member->pivot->gi][$member->pivot->team]
                                = ['ids' => [$member->id], 'kd' => $member->kd];
                        } else {
                            $teams[$member->pivot->gi][$member->pivot->team]['ids'][] = $member->id;
                            $teams[$member->pivot->gi][$member->pivot->team]['kd'] += $member->kd;
                        }
                    }

                    $teams[0] = collect($teams[0])->sortBy('kd');
                    $teams[1] = collect($teams[1])->sortBy('kd');

//                echo "\n0\n";
//                echo $teams[0]->map(function ($q, $team) {
//                    return $team . ': ' . $q['kd'] . ' - ' . implode(', ', $q['ids']);
//                })->join("\n");
//
//                echo "\n1\n";
//                echo $teams[1]->map(function ($q, $team) {
//                    return $team . ': ' . $q['kd'] . ' - ' . implode(', ', $q['ids']);
//                })->join("\n");
//
                    while (abs($teams[0]->count() - $teams[1]->count()) > 1) {
                        $team = [];
                        $gi = 0;
                        $teamName = '';
                        if ($teams[0]->count() > $teams[1]->count()) {
                            $team = $teams[0]->pop();
                            $teamName = $this->findEmptyTeam($teams[1], 1);
                            $teams[1][$teamName] = $team;
                            $gi = 1;
                        } else {
                            $team = $teams[1]->shift();
                            $teamName = $this->findEmptyTeam($teams[0], 1);
                            $teams[0][$teamName] = $team;
                            $gi = 0;
                        }

                        foreach ($team['ids'] as $id) {
                            $game->members()->updateExistingPivot($id,
                                [
                                    'team' => $teamName,
                                    'gi' => $gi,
                                ]
                            );
                        }
                    }

                } // TYPE SOLO

                if ($game->type == Game::TYPE_DUO) {
                    // 1. Распределяем пополам всех 0 и 3 кто не в командах

                    $ignore = collect();
                    foreach ($game->members()->wherePivot('gi', null)
                                 ->get()->sortBy('kd') as $idx => $member) {
                        if ($member->kd >= 3) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($team2) / 2),
                                'gi' => 1
                            ]);
                            $team2++;
                            $ignore->push($member->id);
                        } elseif ($member->kd <= 0.0001) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($team1) / 2),
                                'gi' => 0
                            ]);
                            $team1++;
                            $ignore->push($member->id);
                        }
                    }

                    $teams = array();
                    // Распределяем команды
                    foreach ($game->members()->wherePivot('gi', '<>', null)
                                 ->whereNotIn('id', $ignore)
                                 ->get() as $member) {
                        $key = $member->pivot->gi . '-' . $member->pivot->team;
                        if (!isset($teams[$key])) {
                            $teams[$key] = [
                                'gi' => $member->pivot->gi,
                                'team' => $member->pivot->team,
                                'members' => [$member->id],
                                'count' => 1,
                                'kd' => $member->kd
                            ];
                        } else {
                            $teams[$key]['kd'] += $member->kd;
                            $teams[$key]['count']++;
                            array_push($teams[$key]['members'], $member->id);
                        }
                    }

                    $teams = collect($teams)->filter(function ($item) {
                        return $item['count'] > 1;
                    })->sortBy('kd');


                    $baseTeam1 = $team1;
                    $baseTeam2 = $team2;
                    $team1 = 0;
                    $team2 = 0;

                    while ($teams->isNotEmpty()) {
                        if (100 - $team1 >= $baseTeam1 + 2) {
                            $team = $teams->shift();
                            $game->members()->updateExistingPivot($team['members'][0], [
                                'team' => 'team ' . ceil((100 - $team1) / 2),
                                'gi' => 0
                            ]);
                            $game->members()->updateExistingPivot($team['members'][1], [
                                'team' => 'team ' . ceil((100 - $team1) / 2),
                                'gi' => 0
                            ]);
                            $team1 -= 2;
                        }

                        if (($team2 >= $baseTeam2 + 2) && $teams->isNotEmpty()) {
                            $team = $teams->pop();
                            $game->members()->updateExistingPivot($team['members'][0], [
                                'team' => 'team ' . ceil((100 - $team2) / 2),
                                'gi' => 1
                            ]);
                            $game->members()->updateExistingPivot($team['members'][1], [
                                'team' => 'team ' . ceil((100 - $team2) / 2),
                                'gi' => 1
                            ]);
                            $team2 -= 2;
                        }
                    }

                    // Теперь распихиваем оставшихся людей
                    $later = $game->members()->whereNotIn('id', $ignore->toArray())
                        ->get()->sortBy('kd');
                    foreach ($later as $member) {
                        if ($member->kd >= 3) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($baseTeam2) / 2),
                                'gi' => 1
                            ]);
                            $baseTeam2++;
                            $ignore->push($member->id);
                        } elseif ($member->kd <= 0.0001) {
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($baseTeam1) / 2),
                                'gi' => 0
                            ]);
                            $baseTeam1++;
                            $ignore->push($member->id);
                        }
                    }

                    $later = $game->members()->whereNotIn('id', $ignore->toArray())
                        ->get()->sortBy('kd');

                    while (!$later->isEmpty()) {

                        if ($baseTeam1 < 101 - $team1) {
                            $member = $later->shift();
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($baseTeam1) / 2),
                                'gi' => 0
                            ]);
                            $baseTeam1++;
                        }

                        if (($baseTeam2 < 101 - $team2) && !$later->isEmpty()) {
                            $member = $later->pop();
                            $game->members()->updateExistingPivot($member->id, [
                                'team' => 'team ' . ceil(($baseTeam2) / 2),
                                'gi' => 1
                            ]);
                            $baseTeam2++;
                        }
                    }
                }

                // Если команды не равного размера делим поровну
                $teams = [0 => [], 1 => []];
                foreach ($game->members()->get() as $member) {
                    if (!isset($teams[$member->pivot->gi][$member->pivot->team])) {
                        $teams[$member->pivot->gi][$member->pivot->team]
                            = ['ids' => [$member->id], 'kd' => $member->kd];
                    } else {
                        $teams[$member->pivot->gi][$member->pivot->team]['ids'][] = $member->id;
                        $teams[$member->pivot->gi][$member->pivot->team]['kd'] += $member->kd;
                    }
                }

                $teams[0] = collect($teams[0])->sortBy('kd');
                $teams[1] = collect($teams[1])->sortBy('kd');

//                echo "\n0\n";
//                echo $teams[0]->map(function ($q, $team) {
//                    return $team . ': ' . $q['kd'] . ' - ' . implode(', ', $q['ids']);
//                })->join("\n");
//
//                echo "\n1\n";
//                echo $teams[1]->map(function ($q, $team) {
//                    return $team . ': ' . $q['kd'] . ' - ' . implode(', ', $q['ids']);
//                })->join("\n");
//
                while (abs($teams[0]->count() - $teams[1]->count()) > 1) {
                    $team = [];
                    $gi = 0;
                    $teamName = '';
                    if ($teams[0]->count() > $teams[1]->count()) {
                        $team = $teams[0]->pop();
                        $teamName = $this->findEmptyTeam($teams[1]);
                        $teams[1][$teamName] = $team;
                        $gi = 1;
                    } else {
                        $team = $teams[1]->shift();
                        $teamName = $this->findEmptyTeam($teams[0]);
                        $teams[0][$teamName] = $team;
                        $gi = 0;
                    }

                    foreach ($team['ids'] as $id) {
                        $game->members()->updateExistingPivot($id,
                            [
                                'team' => $teamName,
                                'gi' => $gi,
                            ]
                        );
                    }
                }

//                die();
                event(new GamesChanged());

                // Уведомляем участников
                foreach ($game->members()->get() as $member) {
                    $gameAr = $game->toArray();
                    if ($member->pivot->gi == 1) {
                        $gameAr['login'] = $gameAr['login2'];
                        $gameAr['password'] = $gameAr['password2'];
                    }
                    $member->notify(new GameCredentials($gameAr, str_replace('team ', '', $member->pivot->team)));
                }
            }
        }

//        die();
        // Королевские через 10 минут
        // Мирмар
        $games = Game::where('is_king', true)
            ->where('planned_at2', '>=', now()->addMinutes(10)->startOfMinute())
            ->where('planned_at2', '<=', now()->addMinutes(10)->endOfMinute())
            ->get();

        /** @var Game $game */
        foreach ($games as $game) {
            // Уведомляем участников
            foreach ($game->members()->get() as $member) {
                $gameAr = $game->toArray();
                $gameAr['login'] = $gameAr['login2'];
                $gameAr['password'] = $gameAr['password2'];
                $member->notify(new GameCredentials($gameAr, str_replace('team ', '', $member->pivot->team)));
            }
        }

        // Викенди
        $games = Game::where('is_king', true)
            ->where('planned_at3', '>=', now()->addMinutes(10)->startOfMinute())
            ->where('planned_at3', '<=', now()->addMinutes(10)->endOfMinute())
            ->get();

        /** @var Game $game */
        foreach ($games as $game) {
            // Уведомляем участников
            foreach ($game->members()->get() as $member) {
                $gameAr = $game->toArray();
                $gameAr['login'] = $gameAr['login3'];
                $gameAr['password'] = $gameAr['password3'];
                $member->notify(new GameCredentials($gameAr, str_replace('team ', '', $member->pivot->team)));
            }
        }

        event(new GamesChanged());
    }
}
