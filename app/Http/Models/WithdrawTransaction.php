<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawTransaction extends Transaction
{

    protected $casts = ['comment' => 'json'];

    public function scopeIsWithdraw($q)
    {
        return $q->where('type', Transaction::TYPE_WITHDRAW_PAYPAL);
    }

    public function scopeIsNew($q)
    {
        return $q->where('status', Transaction::STATUS_NORMAL);
    }

    public function scopeIsCanceled($q)
    {
        return $q->where('status', Transaction::STATUS_CANCELED);
    }
}
