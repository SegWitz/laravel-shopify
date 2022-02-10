<?php

namespace Segwitz\ShopifyApp\Test\Storage\Models;

use Segwitz\ShopifyApp\Contracts\ShopModel as IShopModel;
use Segwitz\ShopifyApp\Objects\Enums\ChargeStatus;
use Segwitz\ShopifyApp\Objects\Enums\ChargeType;
use Segwitz\ShopifyApp\Objects\Values\ChargeId;
use Segwitz\ShopifyApp\Objects\Values\ChargeReference;
use Segwitz\ShopifyApp\Storage\Models\Charge;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class ChargeTest extends TestCase
{
    public function testModel(): void
    {
        // Create a shop
        $shop = factory($this->model)->create();

        // Create a charge
        $charge = factory(Util::getShopifyConfig('models.charge', Charge::class))->states('type_recurring')->create([
            'user_id' => $shop->getId()->toNative(),
        ]);

        $this->assertInstanceOf(ChargeId::class, $charge->getId());
        $this->assertInstanceOf(ChargeReference::class, $charge->getReference());
        $this->assertInstanceOf(IShopModel::class, $charge->shop);
        $this->assertNull($charge->plan);
        $this->assertFalse($charge->isTest());
        $this->assertFalse($charge->isTrial());
        $this->assertTrue($charge->isType(ChargeType::RECURRING()));
        $this->assertTrue($charge->isStatus(ChargeStatus::ACCEPTED()));
        $this->assertFalse($charge->isActive());
        $this->assertTrue($charge->isAccepted());
        $this->assertFalse($charge->isDeclined());
        $this->assertFalse($charge->isCancelled());
        $this->assertFalse($charge->isOngoing());
        $this->assertSame('recurring_application_charge', $charge->getTypeApiString());
        $this->assertSame('recurring_application_charges', $charge->getTypeApiString(true));
    }
}
