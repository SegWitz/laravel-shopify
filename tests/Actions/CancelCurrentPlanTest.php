<?php

namespace Segwitz\ShopifyApp\Test\Actions;

use Segwitz\ShopifyApp\Actions\CancelCurrentPlan;
use Segwitz\ShopifyApp\Storage\Models\Plan;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class CancelCurrentPlanTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Actions\CancelCurrentPlan
     */
    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = $this->app->make(CancelCurrentPlan::class);
    }

    public function testCancelWithNoPlan(): void
    {
        // Create the shop with no plan attached
        $shop = factory($this->model)->create();

        $result = call_user_func(
            $this->action,
            $shop->getId()
        );

        $this->assertFalse($result);
    }

    public function testCancelWithPlanButNoCharge(): void
    {
        // Create a plan
        $plan = factory(Util::getShopifyConfig('models.plan', Plan::class))->states('type_recurring')->create();

        // Create the shop with the plan attached
        $shop = factory($this->model)->create([
            'plan_id' => $plan->getId()->toNative(),
        ]);

        $result = call_user_func(
            $this->action,
            $shop->getId()
        );

        $this->assertFalse($result);
    }
}
