<?php

namespace Segwitz\ShopifyApp\Test\Actions;

use Segwitz\ShopifyApp\Actions\DeleteWebhooks;
use Segwitz\ShopifyApp\Test\Stubs\Api as ApiStub;
use Segwitz\ShopifyApp\Test\TestCase;

class DeleteWebhooksTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Actions\DeleteWebhooks
     */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = $this->app->make(DeleteWebhooks::class);
    }

    public function testShouldDelete(): void
    {
        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses([
            'get_webhooks',
            'delete_webhook',
            'delete_webhook',
        ]);

        // Create the shop
        $shop = factory($this->model)->create();

        // Run
        $result = call_user_func($this->action, $shop->getId());

        $this->assertCount(2, $result); // 2 from fixture file
    }
}
