<?php

namespace App\Http\Controllers;

use App\Events\StatUpdated;
use App\Models\User;
use App\Notifications\NewReferrer;
use App\Notifications\PaymentAccepted;
use App\Notifications\RegistrationSuccess;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/api/user';

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gameCodes' => ['required', 'array']
        ]);

        $userData = [
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'games' => 0,
            'kills' => 0,
            'balance' => 0,
            'email_verified_at' => now(),
            'rank_id' => 1,
            'active' => true,
            'selected_games' => $data['gameCodes'],
        ];

        $joinTo = $request->get('joinTo', '');
        if ($joinTo) {
            $joinUser = User::where('name', $joinTo)
                ->orWhere('pubg_id', $joinTo)
                ->orWhere('reflink', $joinTo)
                ->orWhere('id', $joinTo)
                ->first();
            if ($joinUser) {
                $userData['referral_id'] = $joinUser->id;
            }
        }

        if (isset($userData['referral_id']) && ($userData['referral_id'] == 7603)) {
//            if (User::where('referral_id', 7603)->count() < 10400) {
            $userData['rank_id'] = 2;
//            }
        }

        if (isset($userData['referral_id']) && ($userData['referral_id'] == 174)) {
//            if (User::where('referral_id', 7603)->count() < 10400) {
            $userData['rank_id'] = 2;
//            }
        }

        $newUser = User::create($userData);


        if ($newUser->id > 3953 && $newUser->id < 4054) {
            $newUser->rank_id = 2;
            $newUser->save();
        }


        auth()->login($newUser);

        $newUser->notify(new RegistrationSuccess());

        if ($newUser->referral) {
            $newUser->referral->notify(new NewReferrer($newUser->name));
        }

        event(new StatUpdated());

        return ['success' => true, 'user' => $this->user()];
    }

    public function user()
    {
        $user = Auth::user();
        $user->load(['rank']);
        return $user;
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), [
            'active' => true
        ]);
    }

//    public function login(Request $request)
//    {
//        $request->validate([
//            $this->username() => 'required|string',
//            'password' => 'required|string',
//        ]);
//
//        Auth::guard()->
//    }
}
