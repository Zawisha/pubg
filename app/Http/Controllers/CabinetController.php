<?php

namespace App\Http\Controllers;

use App\Classes\PayPalClient;
use App\Events\UserChanged;
use App\Models\Game;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class CabinetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function payForGame(Game $game)
    {
        $user = Auth::user();
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => null,
            'amount' => $game->price,
            // 'merchant' => config('payment.activeMerchant'),
            'merchant' => 'UNITPAY',
            'merchant_id' => null,
            'status' => Payment::STATUS_NEW,
            'comment' => 'Game payment #' . $game->id,
        ]);

        // if (config('payment.activeMerchant') == 'PAYPAL') {
        //     return ['url' => $payment];
        // }

        return ['url' => $payment->getUrl()];
    }


    public function payForFree($amount)
    {
        $user = Auth::user();
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => null,
            'amount' => $amount,
            'merchant' => config('payment.activeMerchant'),
            'merchant_id' => null,
            'status' => Payment::STATUS_NEW,
            'comment' => 'Free payment',
        ]);

        if (config('payment.activeMerchant') == 'PAYPAL') {
            return ['url' => $payment];
        }

        return ['url' => $payment->getUrl()];
    }

    public function payForUn($amount)
    {
        $user = Auth::user();
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => null,
            'amount' => $amount,
            'merchant' => 'UNITPAY',
            'merchant_id' => null,
            'status' => Payment::STATUS_NEW,
            'comment' => 'Free payment',
        ]);
        return ['url' => $payment->getUrl()];
    }


    public function payFree(Game $game)
    {
        $user = Auth::user();
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => null,
            'amount' => $game->price,
            'merchant' => config('payment.activeMerchant'),
            'merchant_id' => null,
            'status' => Payment::STATUS_NEW,
            'comment' => '',
        ]);

        if (config('payment.activeMerchant') == 'PAYPAL') {
            return ['url' => $payment];
        }

        return ['url' => $payment->getUrl()];
    }

    /**
     * Загрузка нового аватара
     * @param Request $request
     */
    public function uploadAvatar(Request $request)
    {
        $file = $request->file('img');
        $user = Auth::user();
        $fileName = $user->id . now()->getTimestamp() . '.jpg';
        $file->move(public_path('/images/upload'), $fileName);

        $filePath = '/images/upload/' . $fileName;

        $user->avatar = $filePath;
        $user->save();

        return [
            'url' => $filePath
        ];
    }

    public function setNameAndId(Request $request)
    {
        $data = $request->only(['game_name', 'game_id', 'vk_link', 'game']);
        $user = Auth::user();
        if (isset($data['game_name'])) {
            $columnName = 'name';

            if ($data['game'] == 'cod') {
                $columnName = 'name_cod';
            };

            if ($data['game'] == 'freefire') {
                $columnName = 'name_freefire';
            };

            if (User::where($columnName, $data['game_name'])
                ->where('id', '<>', $user->id)
                ->first()) {
                abort(422);
            }
            $user->{$columnName} = $data['game_name'];
        }

        if (isset($data['vk_link'])) {
            $user->vk_link = $data['vk_link'];
        }

        if (isset($data['game_id'])) {
            if (User::where($data['game'] . '_id', $data['game_id'])
                ->where('id', '<>', $user->id)
                ->first()) {
                abort(422);
            }
            $user->{$data['game'] . '_id'} = $data['game_id'];
        }

        $user->save();

        event((new UserChanged($user)));

        return $user;
    }

    public function checkPayPal($orderId)
    {
//        $orderId = $request->get('orderId', '');
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderId));
        Log::debug(json_encode($response->result, JSON_PRETTY_PRINT));

        $paymentId = $response->result->purchase_units[0]->custom_id;

        if (App::call('\App\Http\Controllers\PaymentController@confirmPayment',
            [
                'input' => ['intid' => $response->result->id,
                    'sign' => true,
                    'amount' => (float)($response->result->purchase_units[0]->amount->value)
                ],
                'paymentId' => $paymentId,
                'payPal' => true,
            ]
        )) {
            return [
                'status' => 'ok',
            ];
        } else {
            return [
                'status' => 'error'
            ];
        }
    }
}
