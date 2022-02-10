<?php

namespace Segwitz\ShopifyApp\Storage\Queries;

use Illuminate\Support\Collection;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopDomain as ShopDomainValue;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopId as ShopIdValue;
use Segwitz\ShopifyApp\Contracts\Queries\Shop as IShopQuery;
use Segwitz\ShopifyApp\Contracts\ShopModel;
use Segwitz\ShopifyApp\Util;

/**
 * Represents shop queries.
 */
class Shop implements IShopQuery
{
    /**
     * The shop model (configurable).
     *
     * @var ShopModel
     */
    protected $model;

    /**
     * Setup.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = Util::getShopifyConfig('user_model');
    }

    /**
     * {@inheritdoc}
     */
    public function getByID(ShopIdValue $shopId, array $with = [], bool $withTrashed = false): ?ShopModel
    {
        $result = $this->model::with($with);
        if ($withTrashed) {
            $result = $result->withTrashed();
        }

        return $result
            ->where('id', $shopId->toNative())
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getByDomain(ShopDomainValue $domain, array $with = [], bool $withTrashed = false): ?ShopModel
    {
        $result = $this->model::with($with);
        if ($withTrashed) {
            $result = $result->withTrashed();
        }

        return $result
            ->where('name', $domain->toNative())
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(array $with = []): Collection
    {
        return $this->model::with($with)
            ->get();
    }
}
