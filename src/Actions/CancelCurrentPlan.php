<?php

namespace Segwitz\ShopifyApp\Actions;

use Segwitz\ShopifyApp\Contracts\Commands\Charge as IChargeCommand;
use Segwitz\ShopifyApp\Contracts\Queries\Shop as IShopQuery;
use Segwitz\ShopifyApp\Objects\Values\ShopId;
use Segwitz\ShopifyApp\Services\ChargeHelper;

/**
 * Cancels the shop's current plan (in the database).
 */
class CancelCurrentPlan
{
    /**
     * The querier for shops.
     *
     * @var IShopQuery
     */
    protected $shopQuery;

    /**
     * The commands for charges.
     *
     * @var IChargeCommand
     */
    protected $chargeCommand;

    /**
     * The charge helper.
     *
     * @var ChargeHelper
     */
    protected $chargeHelper;

    /**
     * Setup.
     *
     * @param IShopQuery     $shopQuery     The querier for shops.
     * @param IChargeCommand $chargeCommand The commands for charges.
     * @param ChargeHelper   $chargeHelper  The charge helper.
     *
     * @return void
     */
    public function __construct(IShopQuery $shopQuery, IChargeCommand $chargeCommand, ChargeHelper $chargeHelper)
    {
        $this->shopQuery = $shopQuery;
        $this->chargeCommand = $chargeCommand;
        $this->chargeHelper = $chargeHelper;
    }

    /**
     * Execution.
     *
     * @param ShopId $shopId The shop ID.
     *
     * @return bool
     */
    public function __invoke(ShopId $shopId): bool
    {
        // Get the shop and its plan
        $shop = $this->shopQuery->getById($shopId);
        $plan = $shop->plan;

        if (! $plan) {
            // Shop has no plan...
            return false;
        }

        // Cancel the last charge
        $planCharge = $this->chargeHelper->chargeForPlan($shop->plan->getId(), $shop);
        if ($planCharge && ! $planCharge->isDeclined() && ! $planCharge->isCancelled()) {
            $this->chargeCommand->cancel($planCharge->getReference());

            // Charge has been cancelled
            return true;
        }

        // Shop had a plan with no charge attached, usually means its a custom plan
        return false;
    }
}
