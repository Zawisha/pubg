<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/646209266:AAGumg3FhYVmLC-JqYoTap1m10tbeAe3wE4/webhook',
        '/payment',
        '/payment/success',
        '/payment/fail',
        '/api/login',
        '/api/register'
    ];
}
