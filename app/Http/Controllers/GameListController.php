<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Notifications\GameEnded;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GameListController extends Controller
{
    public function get_list_mob(Request $request)
    {
        $user = null;
        if (auth()->user()) {
            $user = auth()->user();
            $user->load(['rank']);

            if (!$user->active) {
                return redirect('/logout');
            }
        }

        $offset = $request->input('offset');
        $time = $request->input('time');
        $from_time = $request->input('from_time');
        $game_type = $request->input('game_type');

        //ask about isking
//3 hours delay
        $correct_time=3;
        $from=time()+ (60*60*$correct_time)+(60*60*$from_time);
        $to= time() + (60*60*$correct_time)+(60*60*$time);
        $from=date('Y-m-d H:i:s',$from);
        $to=date('Y-m-d H:i:s',$to);
        if($time==24)
        {
            $to=date('Y-m-d',time()+60*60*24);
        }
        if($time==48)
        {
            $from=date('Y-m-d',time()+60*60*24);
            $to=date('Y-m-d',time()+60*60*48);
        }
        $games=Game::where('status', '=', Game::STATUS_NEW)
            ->where('game_code', $game_type)
            ->whereBetween('planned_at',[$from, $to])
            ->offset($offset)
            ->limit(10)

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

            ->orderBy('planned_at','asc')
            ->get();
        $lang_game='';
        $geo = \geoip(\request()->getClientIp());
        if (in_array($geo->iso_code, [
            'RU', 'KZ', 'UA', 'BY', 'UZ', 'TJ', 'KG', 'AM', 'GE'
        ])) {
            $lang_game='ru';
        } else {
                $lang_game='en';
        }
        $ru_month = array( 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря' );
        $en_month = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

        foreach ($games as $numb=>$game) {
         if($lang_game=='en')
         {
             $games[$numb]['time_to']=$game->planned_at->format('d F').' at '.$game->planned_at->format('H:i');
         }
           else
           {

               for ($i = 0; $i <count($en_month); $i++) {
                   if($en_month[$i]==$game->planned_at->format('F'))
                   {
                       $game->created_at->format('F');
                        $time_to=$game->planned_at->format('d').' '.$ru_month[$i].' в '.$game->planned_at->format('H:i');
                        $games[$numb]['time_to']=$time_to;
                   }
               }
           }

        }

        return $games;
    }

public function check_time(Request $request)
{
    $check_time_ar = $request->input('check_time_ar');
    $res_arr_games=[];
    if (auth()->user()) {
        $id=auth()->user()->id;
        $user_games = DB::table('game_user')->where('user_id',$id)->get();
        foreach ($user_games as $numb=>$game) {
         //   return dd((get_object_vars($user_games[$numb]))['game_id']);
            if(in_array((get_object_vars($user_games[$numb]))['game_id'], $check_time_ar))
            {
                array_push($res_arr_games, (get_object_vars($user_games[$numb]))['game_id']);
            }
        }

    $games=Game::whereIn('id', $res_arr_games)
        ->get();
    return $games;
    }
}

    public function show_img_results_func(Request $request)
    {
        $game = $request->get('game');
        //CHANGE HERE
//        $game['id']=16;
        $dbGame = Game::find($game['id']);
        $gameIndex = $request->get('index', 0);



        $dbGame->load('members');

        $date = $dbGame->planned_at;
        $date->setTimezone('Europe/Moscow');

        $winTableSorted = [];
        $winTable = collect($dbGame['members']);
        $winTable = $winTable->sort(function ($a, $b) {
            if ($a['pivot']['kills'] == $b['pivot']['kills']) {
                return 0;
            }
            return ($a['pivot']['kills'] > $b['pivot']['kills']) ? -1 : 1;
        });

        foreach ($winTable as $key => $val) {
            if ($val['pivot']['visit']) {
                $winTableSorted[] = [
                    'name' => $val['name'],
                    'kills' => $val['pivot']['kills']
                ];
            }
        };

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


        $date = new DateTime();
        $date_time_old=$date->getTimestamp();
//        $date_time_new=$date_time_old;
        $date_time_old+=5;

        do {
            if (file_exists($imageName)) {

                break;
            }
            $date = new DateTime();
            $date_time_new=$date->getTimestamp();
          //  return dd($date_time_new.'  '. $date_time_old);
//            $date_time_new++;

            if ($date_time_new>$date_time_old) {

                break;
            }
        } while(true);

        return $imageName;

    }

    public function delete_show_img_results_func(Request $request)
    {
        $img_path = $request->input('img_path');
        unlink($img_path);
        return $img_path;
    }

    public function get_list_mob_all(Request $request)
    {
        $user = null;
        if (auth()->user()) {
            $user = auth()->user();
            $user->load(['rank']);

            if (!$user->active) {
                return redirect('/logout');
            }
        }
        $offset = $request->input('offset');
        $game_type = $request->input('game_type');
        //ask about isking

        $games=Game::where('status', '=', Game::STATUS_FINISHED)
            ->where('game_code', $game_type)
            ->offset($offset)
            ->limit(10)
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
            ->orderBy('planned_at','desc')
            ->get();
        return $games;
    }
}
