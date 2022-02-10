<?php

namespace Segwitz\ShopifyApp\Test\Actions;

use Segwitz\ShopifyApp\Actions\GetPlanUrl;
use Segwitz\ShopifyApp\Objects\Values\NullablePlanId;
use Segwitz\ShopifyApp\Storage\Models\Plan;
use Segwitz\ShopifyApp\Test\Stubs\Api as ApiStub;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class GetPlanUrlTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Actions\GetPlanUrl
     */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = $this->app->make(GetPlanUrl::class);
    }

    public function testRun30Days(): void
    {
        // Create a plan
        factory(Util::getShopifyConfig('models.plan', Plan::class))->states(['installable', 'type_recurring'])->create();

        // Create the shop with no plan
        $shop = factory($this->model)->create();

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['post_recurring_application_charges']);

        $result = call_user_func(
            $this->action,
            $shop->getId(),
            NullablePlanId::fromNative(null)
        );

        $this->assertNotEmpty($result);
    }

    public function testRunAnnual(): void
    {
        // Create a plan
        factory(Util::getShopifyConfig('models.plan', Plan::class))->states(['installable', 'type_recurring', 'interval_annual'])->create();

        // Create the shop with no plan
        $shop = factory($this->model)->create();

        // Setup API stub
        $this->setApiStub();
        ApiStub::stubResponses(['graphql_app_subscription_create']);

        $result = call_user_func(
            $this->action,
            $shop->getId(),
            NullablePlanId::fromNative(null)
        );

        $this->assertNotEmpty($result);
    }
}
