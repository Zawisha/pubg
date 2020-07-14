<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function saved(User $user)
    {
        try {
            $admin = Auth::user();
            if ($admin && $admin instanceof Admin) {
                if ($user->balance != $user->getOriginal('balance')) {
                    debug('Balance changed! Creating transaction');

                    $user->transactions()->create([
//                'user_id' => $user->id,
                        'amount' => $user->balance - $user->getOriginal('balance'),
                        'type' => Transaction::TYPE_ADMIN,
                        'status' => Transaction::STATUS_NORMAL,
                        'comment' => json_encode([
                            'admin_id' => Auth::user()->id,
                            'admin_name' => Auth::user()->name,
                        ])
                    ]);
                }
            }
        } catch (\Throwable $er) {
            Log::debug($er);
        }
    }
}
