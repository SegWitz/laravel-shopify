<?php

namespace Segwitz\ShopifyApp\Test\Actions;

use Illuminate\Support\Facades\Queue;
use Segwitz\ShopifyApp\Actions\DispatchScripts;
use Segwitz\ShopifyApp\Messaging\Jobs\ScripttagInstaller;
use Segwitz\ShopifyApp\Test\Stubs\Api as ApiStub;
use Segwitz\ShopifyApp\Test\TestCase;

class DispatchScriptsTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Actions\DispatchScripts
     */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = $this->app->make(DispatchScripts::class);
    }

    public function testRunDispatchOnNoScripts(): void
    {
        // Fake the queue
        Queue::fake();

        // Create the shop
        $shop = factory($this->model)->create();

        // Run
        $result = call_user_func(
            $this->action,
            $shop->getId(),
            false // async
        );

        Queue::assertNotPushed(ScripttagInstaller::class);
        $this->assertFalse($result);
    }

    public function testRunDispatch(): void
    {
        // Fake the queue
        Queue::fake();

        // Create the config
        $this->app['config']->set('shopify-app.scripttags', [
            [
                'src' => 'https://js-aplenty.com/foo.js',
            ],
        ]);

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['get_script_tags']);

        // Create the shop
        $shop = factory($this->model)->create();

        // Run
        $result = call_user_func(
            $this->action,
            $shop->getId(),
            false // async
        );

        Queue::assertPushed(ScripttagInstaller::class);
        $this->assertTrue($result);
    }

    public function testRunDispatchNow(): void
    {
        // Fake the queue
        Queue::fake();

        // Create the config
        $this->app['config']->set('shopify-app.scripttags', [
            [
                'src' => 'https://js-aplenty.com/foo.js',
            ],
        ]);

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['get_script_tags', 'post_script_tags']);

        // Create the shop
        $shop = factory($this->model)->create();

        // Run
        $result = call_user_func(
            $this->action,
            $shop->getId(),
            true // sync
        );

        Queue::assertNotPushed(ScripttagInstaller::class);
        $this->assertTrue($result);
    }
}
