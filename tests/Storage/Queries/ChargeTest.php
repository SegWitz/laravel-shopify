<?php

namespace Segwitz\ShopifyApp\Test\Storage\Queries;

use Segwitz\ShopifyApp\Contracts\Queries\Charge as IChargeQuery;
use Segwitz\ShopifyApp\Objects\Values\ChargeId;
use Segwitz\ShopifyApp\Objects\Values\ChargeReference;
use Segwitz\ShopifyApp\Objects\Values\ShopId;
use Segwitz\ShopifyApp\Storage\Models\Charge;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class ChargeTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Contracts\Queries\Charge
     */
    protected $query;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $shop;

    public function setUp(): void
    {
        parent::setUp();

        $this->query = $this->app->make(IChargeQuery::class);
        $this->shop = factory($this->model)->create();
    }

    public function testChargeGetById(): void
    {
        // Create a charge
        $charge = factory(Util::getShopifyConfig('models.charge', Charge::class))->states('type_recurring')->create([
            'user_id' => $this->shop->getId()->toNative(),
        ]);

        // Query it
        $this->assertNotNull($this->query->getById($charge->getId()));

        // Query non-existant
        $this->assertNull($this->query->getById(ChargeId::fromNative(10)));
    }

    public function testChargeGetByChargeReference(): void
    {
        // Create a charge
        $charge = factory(Util::getShopifyConfig('models.charge', Charge::class))->states('type_recurring')->create([
            'user_id' => $this->shop->getId()->toNative(),
        ]);

        // Query it
        $this->assertNotNull($this->query->getByReference($charge->getReference()));

        // Query non-existant
        $this->assertNull($this->query->getByReference(ChargeReference::fromNative(10)));
    }

    public function testPlangetByReferenceAndShopId(): void
    {
        // Create a charge
        $charge = factory(Util::getShopifyConfig('models.charge', Charge::class))->states('type_recurring')->create([
            'user_id' => $this->shop->getId()->toNative(),
        ]);

        // Query it
        $this->assertNotNull(
            $this->query->getByReferenceAndShopId($charge->getReference(), $this->shop->getId())
        );

        // Query non-existant
        $this->assertNull($this->query->getByReferenceAndShopId(ChargeReference::fromNative(10), ShopId::fromNative(10)));
    }
}
