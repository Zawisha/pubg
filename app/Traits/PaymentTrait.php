<?php
/**
 * Created by PhpStorm.
 * User: don3d_000
 * Date: 04.09.2019
 * Time: 23:03
 */

namespace App\Traits;


trait PaymentTrait
{
    /**
     * Формирование URL оплаты для FREEKASSA
     *
     * @return string
     */
    protected function getFKUrl()
    {
        $merchant_id = config('payment.merchants.FREEKASSA.id');
        $secret = config('payment.merchants.FREEKASSA.secret1');

        $s = md5("$merchant_id:$this->amount:$secret:{$this->id}");
        return config('payment.merchants.FREEKASSA.url') . "?" .
            http_build_query([
                'm' => $merchant_id,
                'oa' => $this->amount,
                'o' => $this->id,
                's' => $s,
            ]);
    }

    public function calcUnitpaySignature($account, $currency, $desc, $sum, $secretKey)
    {
        $hashStr = $account . '{up}' . $currency . '{up}' . $desc . '{up}' . $sum . '{up}' . $secretKey;
        return hash('sha256', $hashStr);
    }

    /**
     * Формирование URL оплаты для UNITPAY
     *
     * @return string
     */
    protected function getUnitpayUrl()
    {
        $publicKey = '279011-de906';
        $secret = 'd693c4fc697a708a6175799bed69d46a';
        $desc = 'UNITOURN BALANCE';
        $cur = 'USD';
        $locale = 'en';

        return config('payment.merchants.UNITPAY.url') . $publicKey . "?" .
            http_build_query([
                'sum' => $this->amount,
                'account' => $this->id,
                'desc' => $desc,
                'currency' => $cur,
                'locale'=>$locale,
                'signature' => $this->calcUnitpaySignature(
                    $this->id,
                    $cur,
                    $desc,
                    $this->amount,
                    $secret
                )
            ]);
    }

    /**
     * Формирование URL оплаты для ROBOKASSA
     *
     * @return string
     */
    protected function getRKUrl($merchant)
    {
        $merchant_login = config('payment.merchants.' . $merchant . '.id');
        $secret = config('payment.merchants.' . $merchant . '.secret1');

//        Log::debug($secret);
//        Log::debug("$merchant_login:{$this->amount}:{$this->id}:$secret");

        $s = md5("$merchant_login:{$this->amount}:{$this->id}:$secret");

        $name = 'PUBG Balance';

        $q = config('payment.merchants.' . $merchant . '.url') .
            http_build_query([
                'MrchLogin' => $merchant_login,
                'OutSum' => $this->amount,
                'InvId' => $this->id,
                'Desc' => $name,
                'SignatureValue' => $s,
            ]);
        return $q;
    }

    /**
     * Формирование URL оплаты для PAYEER
     *
     * @return string
     */
    protected function getPayeerUrl($merchant)
    {
        $m_shop = config('payment.merchants.' . $merchant . '.id');
        $m_orderid = $this->id;
        $m_amount = number_format($this->amount, 2, '.', '');
        $m_curr = 'USD';
        $m_desc = base64_encode($this->rate->name ?? 'PUBG Balance');
        $m_key = config('payment.merchants.' . $merchant . '.key');

        // Формируем массив для генерации подписи
        $arHash = array(
            'm_shop' => $m_shop,
            'm_orderid' => $m_orderid,
            'm_amount' => $m_amount,
            'm_curr' => $m_curr,
            'm_desc' => $m_desc,
        );

        $sign = strtoupper(hash('sha256', implode(':', $arHash) . ':' . $m_key));

        $arHash['m_sign'] = $sign;

        $q = config('payment.merchants.' . $merchant . '.url') .
            http_build_query($arHash);
        return $q;
    }


    /**
     * Формирование URL оплаты для ROBOKASSA
     *
     * @return string
     */
    protected function getInterkassaUrl($merchant)
    {
        $merchant_login = config('payment.merchants.' . $merchant . '.id');
        $secret = config('payment.merchants.' . $merchant . '.secret1');

//        Log::debug($secret);
//        Log::debug("$merchant_login:{$this->amount}:{$this->id}:$secret");

//        $s = md5("$merchant_login:{$this->amount}:{$this->id}:$secret");

        $q = config('payment.merchants.' . $merchant . '.url') .
            '?' .
            http_build_query([
                'ik_co_id' => $merchant_login,
                'ik_am' => $this->amount,
                'ik_pm_no' => $this->id,
                'ik_desc' => $this->rate->name,
//                'SignatureValue' => $s,
                'ik_cur' => 'RUB',
            ]);
        return $q;
    }

    protected function getFondySignature($merchant_id, $password, $params = array())
    {
        $params['merchant_id'] = $merchant_id;
        $params = array_filter($params, 'strlen');
        ksort($params);
        $params = array_values($params);
        array_unshift($params, $password);
        $params = join('|', $params);
        return (sha1($params));
    }

    /**
     * Формирование URL оплаты для FREEKASSA
     *
     * @return string
     */
    protected function getFondyUrl()
    {
        $merchant_id = config('payment.merchants.FONDY.merchant_id');
        $password = config('payment.merchants.FONDY.password');

        $desc = 'UNITOURN PAYMENT';

        $params = [
            'order_id' => $this->id,
            'merchant_id' => $merchant_id,
            'order_desc' => $desc,
            'amount' => $this->amount * 100,
            'currency' => config('payment.merchants.FONDY.currency'),
            'server_callback_url' => url('/payment'),
            'response_url' => url('/payment/success'),
        ];

        $signature = $this->getFondySignature($merchant_id, $password, $params);

        $params['signature'] = $signature;

        return [
            'url' => config('payment.merchants.FONDY.url'),
            'params' => $params
        ];
    }

    /**
     * Формирование URL для редиректа пользователя на оплату
     * @return string
     */
    public function getUrl()
    {

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'freekassa') {
            return $this->getFKUrl();
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'unitpay') {
            return $this->getUnitpayUrl();
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'robokassa') {
            return $this->getRKUrl($this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'payeer') {
            return $this->getPayeerUrl($this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'interkassa') {
            return $this->getInterkassaUrl($this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') === 'fondy') {
            return $this->getFondyUrl($this->merchant);
        }
    }

    /**
     * Проверка контрольной суммы для FREEKASSA
     *
     * @param $input
     * @return array
     */
    protected function parseFKRequest($input)
    {
        $merchantId = $input['MERCHANT_ID'];
        $amount = $input['AMOUNT'];
        $paymentId = $input['MERCHANT_ORDER_ID'];
        $sign = $input['SIGN'];
        $secret = config('payment.merchants.FREEKASSA.secret2');

        $calcSign = md5("$merchantId:$amount:$secret:$paymentId");

        if ($calcSign !== $sign) {
            return ['sign' => false];
        };

        return [
            'sign' => true,
            'amount' => $amount,
            'intid' => $input['intid'],
        ];
    }

    /**
     * Проверка контрольной суммы для FREEKASSA
     *
     * @param $input
     * @return array
     */
    protected function parseFondyRequest($input)
    {
        $merchant_id = config('payment.merchants.FONDY.merchant_id');
        $password = config('payment.merchants.FONDY.password');

        $signature = $input['signature'];

        unset($input['signature']);

        $calcSignature = $this->getFondySignature($merchant_id, $password, $input);


        if ($signature !== $calcSignature) {
            return ['sign' => false];
        };

        return [
            'sign' => true,
            'amount' => $input['amount'] / 100,
            'intid' => $input['payment_id'],
        ];
    }

    /**
     * Проверка контрольной суммы для UNITPAY
     *
     * @param $input
     * @return array
     */
    protected function parseUnitpayRequest($input)
    {

        $secretKey = 'd693c4fc697a708a6175799bed69d46a';
        $method = $input['method'];
        $amount = $input['params']['orderSum'];
        $params = $input['params'];
        ksort($params);
        $sign = $params['signature'];
        unset($params['sign']);
        unset($params['signature']);
        array_push($params, $secretKey);
        array_unshift($params, $method);
        $calcSign = hash('sha256', join('{up}', $params));

        if ($calcSign !== $sign) {
            return ['sign' => false];
        };

        return [
            'sign' => true,
            'amount' => $amount,
            'intid' => $params['unitpayId'],
        ];
    }

    /**
     * Проверка контрольной суммы для ROBOKASSA
     *
     * @param $input
     * @return array
     */
    protected function parseRKRequest($input, $merchant)
    {
        $amount = $input['OutSum'];
        $paymentId = $input['InvId'];
        $sign = strtoupper($input['SignatureValue']);
        $secret = config('payment.merchants.' . $merchant . '.secret2');

        $calcSign = strtoupper(md5("$amount:$paymentId:$secret"));


        if ($calcSign !== $sign) {
            return ['sign' => false];
        };

        return [
            'sign' => true,
            'amount' => $amount,
            'intid' => $paymentId,
        ];
    }


    /**
     * Проверка контрольной суммы для Payeer
     *
     * @param $input
     * @return array
     */
    protected function parsePayeerRequest($input, $merchant)
    {
        $m_key = config('payment.merchants.' . $merchant . '.key');

        // Формируем массив для генерации подписи
        $arHash = array(
            $input['m_operation_id'],
            $input['m_operation_ps'],
            $input['m_operation_date'],
            $input['m_operation_pay_date'],
            $input['m_shop'],
            $input['m_orderid'],
            $input['m_amount'],
            $input['m_curr'],
            $input['m_desc'],
            $input['m_status']
        );
        // Если были переданы дополнительные параметры, то добавляем их в 10 массив
        if (isset($input['m_params'])) {
            $arHash[] = $input['m_params'];
        }
        // Добавляем в массив секретный ключ
        $arHash[] = $m_key;
        // Формируем подпись
        $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

        // Если подписи совпадают и статус платежа “Выполнен”
        return [
            'sign' => $input['m_sign'] == $sign_hash && $input['m_status'] == 'success',
            'amount' => $input['m_amount'],
            'intid' => $input['m_operation_id'],
        ];
    }


    /**
     * Проверка контрольной суммы для INTERKASSA
     *
     * @param $input
     * @return array
     */
    protected function parseInterkassaRequest($input, $merchant)
    {
        $key = config('payment.merchants.' . $merchant . '.key'); //В данном случае используется "Секретный ключ"

        // Подпись из запроса
        $sign = $input['ik_sign'];
        unset($input['ik_sign']); //удаляем из данных строку подписи
        ksort($input, SORT_STRING); // сортируем по ключам в алфавитном порядке элементы массива
        array_push($input, $key); // добавляем в конец массива "секретный ключ"
        $signString = implode(':', $input); // конкатенируем значения через символ ":"

        // Рассчитаная подпись
        $calcSign = base64_encode(md5($signString, true)); // берем MD5 хэш в бинарном виде по сформированной строке и кодируем в BASE64

        $amount = $input['ik_am'];
        $paymentId = $input['ik_inv_id'];

        if ($calcSign !== $sign) {
            return ['sign' => false];
        };

        return [
            'sign' => true,
            'amount' => $amount,
            'intid' => $paymentId,
        ];
    }

    /**
     * Проверка контрольной суммы платежа
     *
     * @param $input параметры запроса
     * @return array
     */
    public function parseRequest($input)
    {

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'freekassa') {
            return $this->parseFKRequest($input);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'unitpay') {
           return  $this->parseUnitpayRequest($input);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'robokassa') {
            return $this->parseRKRequest($input, $this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'payeer') {
            return $this->parsePayeerRequest($input, $this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'interkassa') {
            return $this->parseInterkassaRequest($input, $this->merchant);
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'fondy') {
            return $this->parseFondyRequest($input);
        }
    }

    /**
     * Подтверждающий ответ для платежной системы
     * @return string
     */
    public function getOKResponse()
    {
        if (config('payment.merchants.' . $this->merchant . '.driver') == 'freekassa') {
            return 'YES';
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'robokassa') {
            return 'OK' . $this->id;
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'payeer') {
            return $this->id . '|success';
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'interkassa') {
            return 'YES';
        }

        return 'OK';
    }

    public function getFailResponse($text)
    {
        if (config('payment.merchants.' . $this->merchant . '.driver') == 'freekassa') {
            return 'error|' . $text;
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'robokassa') {
            return 'error|' . $text;
        }

        if (config('payment.merchants.' . $this->merchant . '.driver') == 'payeer') {
            return $this->id . '|error';
        }

        return 'OK';
    }
}
