<?php

namespace App\Http\Controllers;

use App\Classes\UnitPay;
use App\Events\UserChanged;
use App\Models\PayeerPayment;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\CashbackIncome;
use App\Notifications\PaymentAccepted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function confirmPayment($input, $paymentId, $payPal = false)
    {
        /** @var Payment $payment */

        $payment = Payment::find($paymentId);

        if ($payment->merchant == 'UNITPAY') {

            if ($input['method'] == 'check') {
                $payment->merchant_id = $input['params']['unitpayId'];
                $payment->status = Payment::STATUS_WAITING;
                $payment->save();

                return ['result' => [
                    'message' => 'EVERYTHING IS FINE'
                ]];
            }

            if ($input['method'] == 'error') {
                $payment->merchant_id = $input['params']['unitpayId'];
                $payment->status = Payment::STATUS_ERROR;
                $payment->save();

                return ['result' => [
                    'message' => 'ERROR ACCEPTED'
                ]];
            }

            if ($input['method'] != 'pay') {
                return ['error' => [
                    'message' => 'Unknown method'
                ]];
            }
        }



//        debug($input);
//        debug($paymentId);
//        debug($payPal);
//нет заказа
        if (!$payment) {

//            Log::debug("Unknown payment: $paymentId");
//            Log::debug($input);

            if ($payPal) {
                return false;
            }
            return $this->textResponse('INVALID ORDER ID');
        }

        // Нашли заказ. Теперь проверяем контрольную сумму
        $rq = [];

        if ($payPal) {
            $rq = $input;
        } else {
            $rq = $payment->parseRequest($input);
        }


        if (!$rq['sign']) {
//            Log::debug("Invalid sign!");
//            Log::debug($input);

            if ($payPal) {
                return false;
            }
            return $this->textResponse($payment->getFailResponse('INVALID SIGN'));
        }

        //проверка оплаты

        if ($payment->status !== Payment::STATUS_NEW) {
            if ($payment->status === Payment::STATUS_CONFIRMED) {

//                Log::debug("Payment already confirmed: $paymentId");
//                Log::debug($input);

                if ($payPal) {
                    return true;
                }
                return $this->textResponse($payment->getOKResponse());
            }

            if ($payment->status == Payment::STATUS_ERROR) {

//                Log::debug("Error payment is now payed: $paymentId");
//                Log::debug($input);

                if ($payPal) {
                    return false;
                }
                return $payment->getFailResponse($this->textResponse('fail'));
            }
        }


//        if ($rq['amount'] != $payment->amount) {
//            Log::debug("Incorrect amount!: {$rq['amount']} / {$payment->amount}");
//            Log::debug($input);
//        }

//        SystemEvent::addEvent('PaymentController@Payment'
//            , "Payment confirmed: $paymentId"
//            , $input);
//
//        $order->currency_id = $curId;
        $payment->status = Payment::STATUS_CONFIRMED;
        $payment->merchant_id = $rq['intid'];

        $user = $payment->user;

        $user->increment('balance', $payment->amount);

        $payment->transaction()
            ->create([
                'user_id' => $user->id,
                'amount' => $payment->amount,
                'type' => Transaction::TYPE_PAYMENT,
                'status' => Transaction::STATUS_NORMAL,
                'comment' => '',
            ]);

        $payment->save();

        $user->save();

        $user->notify(new PaymentAccepted($payment->amount));

        // Уведомляем об изменениях баланса
        event(new UserChanged($user));

        if ($user->referral_id) {
            if ($user->referral) {
                $addCashback = true;

                if ($user->referral->cbl) {
                    $addCashback = random_int(0, 100) < $user->referral->cbl;
                    Log::debug('User ' . $user->referral->name . '(' . $user->referral->cbl . ') cashback limit: '
                        . ($addCashback ? 'no' : 'yes'));
                }

                if ($addCashback) {
                    $cashback = round($payment->amount * $user->referral->rank->cashback / 100);
                    $user->referral->transactions()
                        ->create([
                            'amount' => $cashback,
                            'type' => Transaction::TYPE_FEE,
                            'status' => Transaction::STATUS_NORMAL,
                            'comment' => 'user_id:' . $user->id,
                        ]);
                    $user->referral->increment('balance', $cashback);
                    //$reason, $userName, $userId, $amount
                    $user->referral->notify(new CashbackIncome(CashbackIncome::REASON_PAYMENT,
                        $user->name,
                        $user->id,
                        $cashback));

                    // Уведомляем пользователя об изменении баланся
                    event(new UserChanged($user->referral));
                }
            }
        }


//        SysNotification::send(SysNotification::PAYMENT_ACCEPTED, NULL, $user);

//        ChatSendSystem::dispatch($user->id, ['type' => 'financePayment', 'action' => 'refresh']);
//        ChargeFee::dispatch($user, $payment);

        if ($payPal) {
            return true;
        }

        if ($payment->merchant == 'UNITPAY') {
            return [
                'result' => [
                    'message' => 'Payment confirmed'
                ]
            ];
        }

        return $this->textResponse($payment->getOKResponse());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function Payment(Request $request)
    {

        $input = $request->all();

//        Log::debug($input);

        $paymentId = NULL;

        if (isset($input['InvId'])) {
            $paymentId = $input['InvId'];
        }

        if (isset($input['order_id'])) {
            $paymentId = $input['order_id'];
        }

        if (isset($input['MERCHANT_ORDER_ID'])) {
            $paymentId = $input['MERCHANT_ORDER_ID'];
        }

        if (isset($input['m_orderid'])) {
            $paymentId = $input['m_orderid'];
        }

        if (isset($input['ik_pm_no'])) {
            $paymentId = $input['ik_pm_no'];
        }

        if (isset($input['params']['account'])) {
            $paymentId = $input['params']['account'];
        }


        if ($paymentId === NULL) {
            Log::debug("Unknown payment: $paymentId");
            Log::debug($request->all());

            return $this->textResponse('INVALID ORDER ID');
        }

        return $this->confirmPayment($input, $paymentId);
    }

    function checkIp()
    {
        return true;
        if (!in_array($this->getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98'))) {
            die("hacking attempt!");
        }
    }

    function getIP()
    {
        global $_SERVER;

        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    protected function textResponse($text)
    {
        return response($text, 200)->header('Content-Type', 'text/plain');
    }

    public function PaymentSuccess()
    {
        return redirect('/?status=success');
    }

    public function PaymentFail()
    {
        return redirect('/?status=fail');
    }

    const payeerPaysys = [
        'visa' => ['fee' => 4, 'num' => 27313794],
        'mastercard' => ['fee' => 4, 'num' => 27322260]
    ];

    const unitpayPaysys = [
        'yandex' => ['fee' => 2, 'num' => 'yandex'],
        'qiwi' => ['fee' => 2, 'num' => 'qiwi',],
        'visa' => ['fee' => 2, 'num' => 'card'],
        'mastercard' => ['fee' => 2, 'num' => 'card']
    ];

    public function paypalWithdraw($user, $account, $amount, $paysys)
    {
        $user->transactions()->create([
//                'user_id' => $user->id,
            'amount' => -$amount,
            'type' => Transaction::TYPE_WITHDRAW_PAYPAL,
            'status' => Transaction::STATUS_NORMAL,
            'comment' => json_encode([
                'account' => $account,
            ]),
        ]);

        event(new UserChanged($user));

        return ['error' => false];
    }

    public function payeerWidthraw($user, $account, $amount, $paysys)
    {
        // Платежка
        if (empty(self::payeerPaysys[$paysys])) {
            return ['error' => 'INVALID PAYSYSTEM'];
        }

        $psId = self::payeerPaysys[$paysys]['num'];

        if ($paysys == 'visa' || $paysys == 'mastercard') {
            $psId = PayeerPayment::findPSId($paysys);
        }

        $res = [];
        try {
            if ($amount >= 10) {
                $res = PayeerPayment::payoutSelfFee($amount, $account, $psId);
            } else {
                $res = PayeerPayment::payout($amount, $account, $psId);
            }
            Log::debug($res);
        } catch (\Throwable $err) {

        }

        if (!empty($res['historyId'])) { // Операция прошла
            $payment_id = $res['historyId'];


            $user->transactions()->create([
//                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => Transaction::TYPE_WITHDRAW,
                'status' => Transaction::STATUS_NORMAL,
                'comment' => json_encode([
                    'account' => $account,
                    'paysys' => $paysys,
                    'historyId' => $res['historyId']
                ]),
            ]);

            event(new UserChanged($user));

            return ['error' => false];
        } else { // Операция не прошла
            User::where('id', $user->id)->increment('balance', $amount);
            return ['error' => isset($res['errors']) ? print_r($res['errors'], true) : 'unknown error'];
        }
    }

    public function unitpayWithdraw($user, $account, $amount, $paysys)
    {
        // Платежка
        if (empty(self::unitpayPaysys[$paysys])) {
            return ['error' => 'INVALID PAYSYSTEM'];
        }

        $psId = self::unitpayPaysys[$paysys]['num'];

        /** @var Transaction $transaction */
        $transaction = $user->transactions()->create([
            'amount' => -$amount,
            'type' => Transaction::TYPE_WITHDRAW,
            'status' => Transaction::STATUS_NORMAL,
            'comment' => json_encode([
                'account' => $account,
                'paysys' => $paysys,
                'gate' => 'unitpay',
            ]),
        ]);

        $res = [];
        $response = null;
        try {
            $unitpay = new UnitPay(config('payment.unitpay.domain'),
                config('payment.unitpay.secret_key')
            );

            $response = $unitpay->api('massPayment', [
                'sum' => $amount,
                'purse' => $account,
                'login' => config('payment.unitpay.login'),
                'transactionId' => $transaction->id,
                'secretKey' => config('payment.unitpay.secret_key'),
                'paymentType' => $psId,
            ]);
        } catch (\Throwable $err) {
            debug($err);
        }

        debug($response);
        if (isset($response->result)) {
            $transaction->comment = json_encode([
                'account' => $account,
                'paysys' => $paysys,
                'gate' => 'unitpay',
                'historyId' => $response->result->payoutId
            ]);

            event(new UserChanged($user));

            return ['error' => false];
        } else {
            User::where('id', $user->id)->increment('balance', $amount);
            return ['error' => $response->error->message ?? 'unknown error'];
        }
//        User::where('id', $user->id)->increment('balance', $amount);

//        if (!empty($res['historyId'])) { // Операция прошла
//        $payment_id = $res['historyId'];


//        $user->transactions()->create([
//                'user_id' => $user->id,
//            'amount' => -$amount,
//            'type' => Transaction::TYPE_WITHDRAW,
//            'status' => Transaction::STATUS_NORMAL,
//            'comment' => json_encode([
//                'account' => $account,
//                'paysys' => $paysys,
//                'historyId' => $res['historyId']
//            ]),
//        ]);
//
//        return ['error' => false];
//        } else { // Операция не прошла
//            User::where('id', $user->id)->increment('balance', $amount);
//            return ['error' => isset($res['errors']) ? print_r($res['errors'], true) : 'unknown error'];
//        }
    }

    public function requestWithdraw(Request $request)
    {
        $account = $request->get('account');
        $amount = $request->get('amount');
        $paysys = $request->get('paysys');

        /** @var User $user */
        $user = Auth::user();

        // Проверяем баланс
        if (($user->balance < $amount)
            || ($amount < 4.5)
            || ($amount > 230)) {
            return ['error' => 'WRONG AMOUNT'];
        }

        User::where('id', $user->id)->decrement('balance', $amount);

        if ($paysys == 'paypal') {
            return $this->paypalWithdraw($user, $account, $amount, $paysys);
        }

        if (config('payment.activePayoutSystem') == 'payeer') {
            return $this->payeerWidthraw($user, $account, $amount, $paysys);
        }

        if (config('payment.activePayoutSystem') == 'unitpay') {
            return $this->unitpayWithdraw($user, $account, $amount, $paysys);
        }
    }
}
