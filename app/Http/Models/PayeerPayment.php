<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PayeerPayment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PayeerPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PayeerPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PayeerPayment query()
 * @mixin \Eloquent
 */
class PayeerPayment extends Model
{
    static public function makeRequest($action, $params = [])
    {
        $url = "https://payeer.com/ajax/api/api.php";

        $params = array_merge($params, [
            'account' => config('payment.payeer.account'),
            'apiId' => config('payment.payeer.id'),
            'apiPass' => config('payment.payeer.key'),
            'action' => $action
        ]);

//        print_r($params);

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request(
                'POST',
                $url,
                ['form_params' => $params]);
            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody(), true);
            } else {
                return false;
            }
        } catch (ClientException $e) {
            if ($e->getCode() == 404) {
                return false;
            };
        }
    }

    //
    static public function checkAuth()
    {
        print_r(self::makeRequest(''));
        //
    }

    static public function getBalance()
    {
        return self::makeRequest("balance");
    }

    static public function payout($sum, $accountNumber, $ps = 26808)
    {
        return self::makeRequest("output", [
            'ps' => $ps,
            'sumIn' => $sum,
            'curOut' => 'USD',
            'curIn' => 'USD',
            'param_ACCOUNT_NUMBER' => $accountNumber,
        ]);
    }

    //@TODO: WITHDRAW2
    static public function payoutSelfFee($sum, $accountNumber, $ps = 26808)
    {
        return self::makeRequest("output", [
            'ps' => $ps,
            'sumOut' => $sum,
            'curOut' => 'USD',
            'curIn' => 'USD',
            'param_ACCOUNT_NUMBER' => $accountNumber,
        ]);
    }

    static public function getHistoryInfo($historyId)
    {
        return self::makeRequest('historyInfo', [
            'historyId' => $historyId
        ]);
    }

    static public function getPaySystems()
    {
        return self::makeRequest('getPaySystems');
    }

    static public function findPSId($psName)
    {
//        print_r(self::getPaySystems());
        foreach (self::getPaySystems()['list'] as $id => $info) {
//            print_r($info);
            if(mb_strtoupper($info['name']) == mb_strtoupper($psName)){
                return $id;
            }
        }

        return 0;
    }
}
