<?php

namespace Segwitz\ShopifyApp\Test\Messaging\Jobs;

use Segwitz\ShopifyApp\Messaging\Jobs\AppUninstalledJob;
use Segwitz\ShopifyApp\Objects\Enums\ChargeStatus;
use Segwitz\ShopifyApp\Storage\Models\Charge;
use Segwitz\ShopifyApp\Storage\Models\Plan;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class AppUninstalledTest extends TestCase
{
    public function testJobSoftDeletesShopAndCharges(): void
    {
        // Create a plan
        $plan = factory(Util::getShopifyConfig('models.plan', Plan::class))->states('type_recurring')->create();

        // Create a shop attached to the plan
        $shop = factory($this->model)->create(['plan_id' => $plan->getId()->toNative()]);

        // Create a charge for the shop and plan
        factory(Util::getShopifyConfig('models.charge', Charge::class))->states('type_recurring')->create([
            'plan_id' => $plan->getId()->toNative(),
            'user_id' => $shop->getId()->toNative(),
            'status' => ChargeStatus::ACTIVE()->toNative(),
        ]);

        // Ensure shop is not trashed, and has charges
        $this->assertFalse($shop->trashed());
        $this->assertTrue($shop->hasCharges());
        $this->assertNotNull($shop->plan);
        $this->assertNotEmpty($shop->password);

        // Run the job
        AppUninstalledJob::dispatchNow(
            $shop->getDomain()->toNative(),
            json_decode(file_get_contents(__DIR__.'/../../fixtures/app_uninstalled.json'))
        );

        // Refresh both models to see the changes
        $shop->refresh();

        // Confirm job worked...
        $this->assertTrue($shop->trashed());
        $this->assertFalse($shop->hasCharges());
        $this->assertNull($shop->plan);
        $this->assertEmpty($shop->password);
    }
}
