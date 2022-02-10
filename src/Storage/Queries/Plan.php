<?php

namespace Segwitz\ShopifyApp\Storage\Queries;

use Illuminate\Support\Collection;
use Segwitz\ShopifyApp\Contracts\Objects\Values\PlanId;
use Segwitz\ShopifyApp\Contracts\Queries\Plan as IPlanQuery;
use Segwitz\ShopifyApp\Storage\Models\Plan as PlanModel;
use Segwitz\ShopifyApp\Util;

/**
 * Represents plan queries.
 */
class Plan implements IPlanQuery
{
    /**
     * the Plan Model.
     *
     * @var PlanModel
     */
    protected $planModel;

    /**
     * Init for charge command.
     */
    public function __construct()
    {
        $chargeClass = Util::getShopifyConfig('models.plan', PlanModel::class);
        $this->planModel = new $chargeClass();
    }


    /**
     * {@inheritdoc}
     */
    public function getById(PlanId $planId, array $with = []): ?PlanModel
    {
        return $this->planModel->with($with)
            ->get()
            ->where('id', $planId->toNative())
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault(array $with = []): ?PlanModel
    {
        return $this->planModel->with($with)
            ->get()
            ->where('on_install', true)
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(array $with = []): Collection
    {
        return $this->planModel->with($with)
            ->get();
    }
}
