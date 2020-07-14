<?php

use App\Models\User;

Route::get('', ['as' => 'admin.dashboard', function () {
    $income = \App\Models\Transaction::where('type', \App\Models\Transaction::TYPE_PAYMENT)
        ->where('created_at', '>=', now()->addHours(3)->startOfDay()->subHours(3))
        ->where('created_at', '<', now()->addHours(3)->endOfDay()->subHours(3))
        ->sum('amount');

    $refPayments = \App\Models\Transaction::where('type', \App\Models\Transaction::TYPE_FEE)
        ->where('created_at', '>=', now()->addHours(3)->startOfDay()->subHours(3))
        ->where('created_at', '<', now()->addHours(3)->endOfDay()->subHours(3))
        ->sum('amount');

    Log::debug(now()->addHours(3)->startOfDay()->subHours(3));

    return AdminSection::view(view('admin.dashboard', [
            'income' => $income,
            'balance' => User::where('balance', '>=', 250)->sum('balance'),
            'echoStat' => app()->environment('local')
                ? ['balance' => 'local']
                : json_decode(file_get_contents(config('pubg.echo_stat_url'))),
            'telegram' => User::whereNotNull('telegram_id')->count(),
            'telegram_ban' => User::where('telegram_ban', true)->count(),
            'refPayments' => $refPayments,
        ])
        , 'Dashboard');
//    return ;
}]);
