<?php

namespace Segwitz\ShopifyApp\Contracts\Queries;

use Segwitz\ShopifyApp\Objects\Values\ChargeId;
use Segwitz\ShopifyApp\Objects\Values\ChargeReference;
use Segwitz\ShopifyApp\Objects\Values\ShopId;
use Segwitz\ShopifyApp\Storage\Models\Charge as ChargeModel;

/**
 * Reprecents a queries for charges.
 */
interface Charge
{
    /**
     * Get by ID.
     *
     * @param ChargeId $chargeId The charge ID.
     * @param array    $with     The relations to eager load.
     *
     * @return ChargeModel|null
     */
    public function getById(ChargeId $chargeId, array $with = []): ?ChargeModel;

    /**
     * Get by charge reference.
     *
     * @param ChargeReference $chargeRef The charge ID.
     * @param array           $with     The relations to eager load.
     *
     * @return ChargeModel|null
     */
    public function getByReference(ChargeReference $chargeRef, array $with = []): ?ChargeModel;

    /**
     * Get by shop ID and charge ID.
     *
     * @param ChargeReference $chargeRef The charge ID from Shopify.
     * @param ShopId          $shopId    The shop's ID for the charge.
     *
     * @return ChargeModel|null
     */
    public function getByReferenceAndShopId(ChargeReference $chargeRef, ShopId $shopId): ?ChargeModel;
}
