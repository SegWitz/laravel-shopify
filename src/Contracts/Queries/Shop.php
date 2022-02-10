<?php

namespace Segwitz\ShopifyApp\Contracts\Queries;

use Illuminate\Support\Collection;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopDomain as ShopDomainValue;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopId as ShopIdValue;
use Segwitz\ShopifyApp\Contracts\ShopModel as IShopModel;

/**
 * Represents a queries for shops.
 */
interface Shop
{
    /**
     * Get by ID.
     *
     * @param ShopIdValue $shopId      The shop ID.
     * @param array       $with        The relations to eager load.
     * @param bool        $withTrashed Include trashed shops?
     *
     * @return IShopModel|null
     */
    public function getById(ShopIdValue $shopId, array $with = [], bool $withTrashed = false): ?IShopModel;

    /**
     * Get by domain.
     *
     * @param ShopDomainValue $domain      The shop domain.
     * @param array      $with        The relations to eager load.
     * @param bool       $withTrashed Include trashed shops?
     *
     * @return IShopModel|null
     */
    public function getByDomain(ShopDomainValue $domain, array $with = [], bool $withTrashed = false): ?IShopModel;

    /**
     * Get all records.
     *
     * @param array $with The relations to eager load.
     *
     * @return Collection IShopModel[]
     */
    public function getAll(array $with = []): Collection;
}
