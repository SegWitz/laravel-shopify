<?php

namespace Segwitz\ShopifyApp\Test\Storage\Queries;

use Segwitz\ShopifyApp\Contracts\Queries\Plan as IPlanQuery;
use Segwitz\ShopifyApp\Objects\Values\PlanId;
use Segwitz\ShopifyApp\Storage\Models\Plan;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class PlanTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Contracts\Queries\Plan
     */
    protected $query;

    public function setUp(): void
    {
        parent::setUp();

        $this->query = $this->app->make(IPlanQuery::class);
    }

    public function testPlanGetById(): void
    {
        // Create a plan
        $plan = factory(Util::getShopifyConfig('models.plan', Plan::class))->states('type_recurring')->create();

        // Query it
        $this->assertNotNull($this->query->getById($plan->getId()));

        // Query non-existant
        $this->assertNull($this->query->getById(PlanId::fromNative(10)));
    }

    public function testPlanGetDefault(): void
    {
        // Query non-existant
        $this->assertNull($this->query->getDefault());

        // Create a plan
        factory(Util::getShopifyConfig('models.plan', Plan::class))->states(['type_recurring', 'installable'])->create();

        // Query it
        $this->assertNotNull($this->query->getDefault());
    }

    public function testPlanGetAll(): void
    {
        // Create a plan
        factory(Util::getShopifyConfig('models.plan', Plan::class))->states('type_onetime')->create();

        // Ensure we get a result
        $this->assertCount(1, $this->query->getAll());
    }
}
