<?php


namespace App\Classes;


class UnitPay
{
    protected $url = '';
    protected $secretKey = '';

    public function __construct($url = null, $secretKey = null)
    {
        $this->url = $url ?: config('payment.unitpay.domain');
        $this->secretKey = $secretKey ?: config('payment.unitpay.secret_key');
    }

    public function api($method, $params)
    {
        $params = array_merge($params, [
            'secretKey' => $this->secretKey,
        ]);

        if (config('payment.unitpay.testMode')) {
            $params = array_merge($params, [
                'test' => 1
            ]);
        }

//        print_r($params);
        $requestUrl = $this->url . '?' . http_build_query([
                'method' => $method,
                'params' => $params
            ], null, '&', PHP_QUERY_RFC3986);

        debug($requestUrl);

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request(
                'GET',
                $requestUrl);
            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody());
            } else {
                return false;
            }
        } catch (ClientException $e) {
            if ($e->getCode() == 404) {
                return false;
            };
        }
    }
}