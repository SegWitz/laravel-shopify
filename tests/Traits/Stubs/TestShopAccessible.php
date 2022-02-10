<?php

namespace Segwitz\ShopifyApp\Test\Traits\Stubs;

use Segwitz\ShopifyApp\Traits\ShopAccessible;

class TestShopAccessible
{
    use ShopAccessible;

    public function hasShop(): bool
    {
        return $this->shop !== null;
    }
}
