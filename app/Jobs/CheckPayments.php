<?php

namespace App\Jobs;

use App\Classes\UnitPay;
use App\Models\PayeerPayment;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\PaymentEPSCancel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Transaction::where('type', Transaction::TYPE_WITHDRAW)
            ->where('status', Transaction::STATUS_NORMAL)
//            ->where('created_at', '<', now()->startOfDay()->subDay())
            ->chunk(1000, function ($transactions) {
                foreach ($transactions as $transaction) {
//                    try {
                    $data = json_decode($transaction->comment);
                    if (isset($data->historyId) && $data->historyId
                        || isset($data->gate) && $data->gate == 'unitpay') {
                        $info = [];
                        if (isset($data->gate)) {
                            if ($data->gate == 'unitpay') {
                                echo 'UNITPAY';
                                $unitpay = new UnitPay();
                                $info = $unitpay->api('massPaymentStatus', [
                                    'transactionId' => $transaction->id,
                                    'login' => config('payment.unitpay.login'),
                                ]);

                                print_r($info);

                                if (isset($info->result)) {
                                    $info = $info->result;
                                } else {
                                    continue;
                                }
                            }
                        } else {
                            $info = PayeerPayment::getHistoryInfo($data->historyId);
                        }

                        if (!is_object($info) && isset($info['info']['status']) && $info['info']['status'] == 'cancel'
                            || isset($info->status) && $info->status != 'success' && $info->status != 'not_completed') {
                            $transaction->status = Transaction::STATUS_CANCELED;
                            $transaction->save();

                            $data->transactionId = $transaction->id;

                            Transaction::create([
                                'user_id' => $transaction->user_id,
                                'amount' => -$transaction->amount,
                                'type' => Transaction::TYPE_EPS_CANCEL,
                                'status' => Transaction::STATUS_NORMAL,
                                'comment' => json_encode($data)
                            ]);

                            User::where('id', $transaction->user_id)
                                ->increment('balance', -$transaction->amount);

                            $transaction->user->notify(new PaymentEPSCancel($transaction));
                        } elseif (!is_object($info) && isset($info['info']['status']) && $info['info']['status'] == 'execute'
                            || isset($info->status) && $info->status == 'success') {
                            $transaction->status = Transaction::STATUS_CONFIRMED;
                            $transaction->save();
                        } else {
                            $data->lastTry = now()->toString();
                            if (!is_object($info)) {
                                $data->status = $info->status ?? 'unknown';
                            } else {
                                $data->status = $info['info']['status'] ?? 'unknown';
                            }
                            $transaction->comment = json_encode($data);
                            $transaction->save();
                        }
                    } else {
                        $transaction->status = Transaction::STATUS_CANCELED;
                        $transaction->save();
                    }
//                    } catch (\Throwable $er) {
//                        Log::debug($er);
//                    }
                }
            });
    }
}
