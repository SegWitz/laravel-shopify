<?php

namespace Segwitz\ShopifyApp\Test\Storage\Queries;

use Segwitz\ShopifyApp\Contracts\Queries\Shop as IShopQuery;
use Segwitz\ShopifyApp\Objects\Values\ShopDomain;
use Segwitz\ShopifyApp\Objects\Values\ShopId;
use Segwitz\ShopifyApp\Test\TestCase;

class ShopTest extends TestCase
{
    /**
     * @var \Segwitz\ShopifyApp\Contracts\Queries\Shop
     */
    protected $query;

    public function setUp(): void
    {
        parent::setUp();

        $this->query = $this->app->make(IShopQuery::class);
    }

    public function testShopGetById(): void
    {
        // Create a shop
        $shop = factory($this->model)->create();

        // Query it
        $this->assertNotNull($this->query->getById($shop->getId()));

        // Query non-existent
        $this->assertNull($this->query->getById(ShopId::fromNative(10)));
    }

    public function testShopGetByDomain(): void
    {
        // Create a shop
        $shop = factory($this->model)->create();

        // Query it
        $this->assertNotNull($this->query->getByDomain($shop->getDomain()));

        // Query non-existent
        $this->assertNull($this->query->getByDomain(ShopDomain::fromNative('non-existent.myshopify.com')));
    }

    public function testShopGetAll(): void
    {
        // Create a shop
        factory($this->model)->create();

        // Ensure we get a result
        $this->assertCount(1, $this->query->getAll());
    }
}
