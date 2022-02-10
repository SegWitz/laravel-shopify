<?php

namespace Segwitz\ShopifyApp\Test\Traits;

use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Test\Traits\Stubs\TestShopAccessible;

class ShopAccessibleTest extends TestCase
{
    public function testSuccess(): void
    {
        $class = new TestShopAccessible();
        $class->setShop(
            factory($this->model)->create()
        );

        $this->assertTrue($class->hasShop());
    }
}
