<?php

namespace App\Http\Controllers;

use App\Models\Blogger;
use App\Models\Content;
use App\Models\Game;
use App\Models\GameRu;
use App\Models\LiveBroadcast;
use App\Models\Rank;
use App\Models\User;
use App\Models\UserRu;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
//        $this->middleware('guest', ['except' => 'logout']);
    }

    public function langJS(Request $request, $lang)
    {
        Cache::forget('lang.' . $lang . '.js');
        $strings = Cache::rememberForever('lang.' . $lang . '.js', function () use ($lang) {
            //$lang = config('app.locale');

            $files = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];

            foreach ($files as $file) {
                $name = basename($file, '.php');
                if ($name != 'admin') {
                    $strings[$name] = require $file;
                }
            }

            return $strings;
        });

        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings, JSON_UNESCAPED_UNICODE) . ';');
        exit();
    }

    public function index($referalId = null)
    {
        /** @var User $user */
        $user = null;
        if (auth()->user()) {
            $user = auth()->user();
            $user->load(['rank']);

            if (!$user->active) {
                return redirect('/logout');
            }
        }

        if ((!$user || \request()->getHost() == 'unitourn.com') && !app()->environment('local')) {
            $geoStr = '';
            try {
                $geo = \geoip(\request()->getClientIp());
                if (in_array($geo->iso_code, [
                    'RU', 'KZ', 'UA', 'BY', 'UZ', 'TJ', 'KG', 'AM', 'GE'
                ])) {
                    return redirect('https://ru.unitourn.com');
                } else {

                   // if (\request()->getHost() != 'en.unitourn.com') {
                     //   return redirect('https://en.unitourn.com');
                 //   }
                }

//                $geo = \geoip('213.87.137.251');
//                $geo = \geoip('176.59.37.197');

//                $geoStr = $geo['country'] . ', ' . $geo['city'];
            } catch (\Throwable $er) {
                Log::error($er);
            }
//            print_r($geo);
//            die();
        }

        $ranks = Rank::all();
        $killValue = $ranks[0]->kill_reward / 100;

        if ($user) {
            $killValue = $user->rank->kill_reward / 100;
            $user->loadCount('subscribers');
        }

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

        $games = Game::whereIn('id', $gamesIds)
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
//            ->notKing()
//            ->take(7)
////            ->take(2)
            ->get();

        $kingGame = Game::whereIn('status', [Game::STATUS_NEW, Game::STATUS_STARTED])
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
            ->isKing()
            ->first();

        $content = Content::pluck('content', 'name');
        $content['how-its-work'] = $content['rules'];

        $stat = [];

        $stat['users'] = User::count() + UserRu::count();
        $stat['games'] = Game::where('status', Game::STATUS_FINISHED)->sum('mul')
            + GameRu::where('status', Game::STATUS_FINISHED)->sum('mul');
        $stat['total_payed'] = Game::select(DB::raw('sum(total_payed2) as tp2, sum(total_payed) as tp'))
            ->first();

        $tpRu = GameRu::select(DB::raw('sum(total_payed2) as tp2, sum(total_payed) as tp'))
            ->first();

        $stat['total_payed'] = $stat['total_payed']->tp + $stat['total_payed']->tp2 +
            round(($tpRu->tp + $tpRu->p2) / 70);

        return view('landing', [
            'ranks' => $ranks,
            'user' => $user,
            'games' => $games,
            'kingGame' => $kingGame,
            'killValue' => $killValue,
            'content' => $content,
            'notifications' => $user ? $user->notifications()->latest()->take(30)->get() : [],
            'liveStreamUrl' => LiveBroadcast::find(1),
            'referalId' => $referalId,
            'stat' => $stat
        ]);
    }

    public function indexPost(Request $request)
    {
        return $this->index();
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('/');
    }

    /**
     * Сохранения реферала в сессии и редирект на главную
     * @param type $referalId
     * @return type
     */
    public function join($referalId)
    {
        $referal = User::where('name', $referalId)
            ->orWhere('pubg_id', $referalId)
            ->orWhere('reflink', $referalId)
            ->first();
        $exists = (Boolean)$referal;

        if ($exists) {
            session(['referral_id' => $referalId]);
            Cookie::queue('referral_name', $referalId, 366 * 24 * 60);

            return $this->index($referalId);
//            return view('landings.user', [
//                'referral' => $referal
//            ]);
            return redirect('/');//?join=' . $referalId);
        } else {
//            abort(404);
//            return redirect('/');
            session(['referral_id' => ""]);
            Cookie::queue('referral_name', '', 366 * 24 * 60);
            abort(404);
            return redirect('/');
        }
    }

    public function authAs($userId)
    {
        if (!(auth('admin')->user())
            || ((!auth('admin')->user()->isSuperAdmin())
                && (!auth('admin')->user()->isAdmin())
                && (!auth('admin')->user()->isManager()))) {
            abort(404);
        } else {
            Auth::loginUsingId($userId);
            return redirect('/');
        }
    }

    public function bloggersRequest(Request $request)
    {
        $data = $request->only(['name',
            'email',
            'phone',
            'vk']);

        return Blogger::create($data);
    }
}
