<?php

namespace App\Classes;


use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = config('paypal.client');
        $clientSecret = config('paypal.secret');
        if (config('paypal.settings.mode') == 'sandbox') {
            return new SandboxEnvironment($clientId, $clientSecret);
        }

        if (config('paypal.settings.mode') == 'production') {
            return new ProductionEnvironment($clientId, $clientSecret);
        }

    }
}