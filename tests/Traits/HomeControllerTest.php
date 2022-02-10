<?php

namespace Segwitz\ShopifyApp\Test\Traits;

use Illuminate\Auth\AuthManager;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class HomeControllerTest extends TestCase
{
    /**
     * @var AuthManager
     */
    protected $auth;

    public function setUp(): void
    {
        parent::setUp();

        $this->auth = $this->app->make(AuthManager::class);
    }

    public function testHomeRoute(): void
    {
        $shop = factory($this->model)->create(['name' => 'shop-name.myshopify.com']);

        $this->call('get', '/', ['token' => $this->buildToken()])
            ->assertOk()
            ->assertSee('apiKey: "'.Util::getShopifyConfig('api_key').'"', false)
            ->assertSee("shopOrigin: \"{$shop->name}\"", false);
    }
}
