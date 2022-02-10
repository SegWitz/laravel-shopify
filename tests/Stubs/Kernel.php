<?php

namespace Segwitz\ShopifyApp\Test\Stubs;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Orchestra\Testbench\Http\Middleware\RedirectIfAuthenticated;
use Segwitz\ShopifyApp\Http\Middleware\AuthProxy;
use Segwitz\ShopifyApp\Http\Middleware\AuthWebhook;
use Segwitz\ShopifyApp\Http\Middleware\Billable;
use Segwitz\ShopifyApp\Http\Middleware\VerifyShopify;

class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'throttle' => ThrottleRequests::class,

        // Added for testing
        'verify.shopify' => VerifyShopify::class,
        'auth.webhook' => AuthWebhook::class,
        'auth.proxy' => AuthProxy::class,
        'billable' => Billable::class,
    ];
}
