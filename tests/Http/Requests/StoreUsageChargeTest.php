<?php

namespace Segwitz\ShopifyApp\Test\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Segwitz\ShopifyApp\Http\Requests\StoreUsageCharge;
use Segwitz\ShopifyApp\Test\TestCase;
use Segwitz\ShopifyApp\Util;

class StoreUsageChargeTest extends TestCase
{
    public function testFailsWithNoCode(): void
    {
        $validator = Validator::make(
            [],
            (new StoreUsageCharge())->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertTrue((new StoreUsageCharge())->authorize());
    }

    public function testFailsForInvalidSignature(): void
    {
        $data = [
            'price' => '1.00',
            'description' => 'Testing',
        ];

        $signature = Util::createHmac(['data' => $data, 'buildQuery' => true], $this->app['config']->get('shopify-app.api_secret'));
        $data['signature'] = $signature->toNative();
        $data['price'] = '2.00';

        $storeUsage = new StoreUsageCharge([], $data);
        $validator = Validator::make($data, $storeUsage->rules());
        $storeUsage->withValidator($validator);

        $this->assertTrue($validator->fails());
    }

    public function testPasses(): void
    {
        $data = [
            'price' => '1.00',
            'description' => 'Testing',
            'redirect' => '/',
        ];
        $signature = Util::createHmac(['data' => $data, 'buildQuery' => true], $this->app['config']->get('shopify-app.api_secret'));
        $data['signature'] = $signature->toNative();

        $storeUsage = new StoreUsageCharge([], $data);
        $validator = Validator::make($data, $storeUsage->rules());
        $storeUsage->withValidator($validator);

        $this->assertFalse($validator->fails());
    }
}
