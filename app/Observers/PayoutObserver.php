<?php

namespace App\Observers;

use App\Events\UserChanged;
use App\Models\Transaction;
use App\Models\WithdrawTransaction;
use App\Notifications\PaymentEPSCancel;

class PayoutObserver
{
    public function saving(WithdrawTransaction $payout)
    {
        // У нас отмена платежа
        if ($payout->status == WithdrawTransaction::STATUS_CANCELED
            && $payout->getOriginal('status') != $payout->status) {

            $t = new Transaction();
            $t->user_id = $payout->user_id;
            $t->amount = -$payout->amount;
            $t->type = Transaction::TYPE_REFUND;
            $t->comment = json_encode([
                'status' => 'Paypal transaction cancelation',
                'related_transaction_id' => $payout->id
            ]);
            $t->save();

            $payout->user->notify(new PaymentEPSCancel($t));

            $payout->user->increment('balance', -$payout->amount);
            event(new UserChanged($payout->user));
//            $payout->status = Transaction::STATUS_CANCELED;
//            $payout->save();
        }
    }

}
