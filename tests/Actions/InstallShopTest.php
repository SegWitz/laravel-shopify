<?php

namespace Segwitz\ShopifyApp\Test\Actions;

use Segwitz\ShopifyApp\Actions\InstallShop;
use Segwitz\ShopifyApp\Objects\Values\ShopDomain;
use Segwitz\ShopifyApp\Test\Stubs\Api as ApiStub;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class InstallShopTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Actions\InstallShop
     */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = $this->app->make(InstallShop::class);
    }

    public function testNoShopShouldBeMade(): void
    {
        $result = call_user_func(
            $this->action,
            ShopDomain::fromNative('non-existant.myshopify.com'),
            null
        );

        $this->assertStringContainsString(
            '/admin/oauth/authorize?client_id='.Util::getShopifyConfig('api_key').'&scope=read_products%2Cwrite_products&redirect_uri=https%3A%2F%2Flocalhost%2Fauthenticate',
            $result['url']
        );
        $this->assertFalse($result['completed']);
        $this->assertNotNull($result['shop_id']);
    }

    public function testWithoutCode(): void
    {
        // Create the shop
        $shop = factory($this->model)->create();

        $result = call_user_func(
            $this->action,
            $shop->getDomain(),
            null
        );

        $this->assertStringContainsString(
            '/admin/oauth/authorize?client_id='.Util::getShopifyConfig('api_key').'&scope=read_products%2Cwrite_products&redirect_uri=https%3A%2F%2Flocalhost%2Fauthenticate',
            $result['url']
        );
        $this->assertFalse($result['completed']);
        $this->assertNotNull($result['shop_id']);
    }

    public function testWithCode(): void
    {
        // Create the shop
        $shop = factory($this->model)->create();

        // Get the current access token
        $currentToken = $shop->getAccessToken();

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['access_token']);

        $result = call_user_func(
            $this->action,
            $shop->getDomain(),
            '12345678'
        );

        // Refresh to see changes
        $shop->refresh();

        $this->assertTrue($result['completed']);
        $this->assertNotNull($result['shop_id']);
        $this->assertNotSame($currentToken->toNative(), $shop->getAccessToken()->toNative());
    }

    public function testWithCodeSoftDeletedShop(): void
    {
        // Create the shop
        $shop = factory($this->model)->create([
            'deleted_at' => $this->now->getTimestamp(),
        ]);

        // Get the current access token
        $currentToken = $shop->getAccessToken();

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['access_token']);

        $result = call_user_func(
            $this->action,
            $shop->getDomain(),
            '12345678'
        );

        // Refresh to see changes
        $shop->refresh();

        $this->assertTrue($result['completed']);
        $this->assertNotNull($result['shop_id']);
        $this->assertNotSame($currentToken->toNative(), $shop->getAccessToken()->toNative());
    }
}
