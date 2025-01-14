<?php

namespace Segwitz\ShopifyApp\Objects\Transfers;

use Illuminate\Support\Carbon;
use Segwitz\ShopifyApp\Contracts\Objects\Values\PlanId;
use Segwitz\ShopifyApp\Objects\Enums\ChargeStatus;
use Segwitz\ShopifyApp\Objects\Enums\ChargeType;
use Segwitz\ShopifyApp\Objects\Values\ShopId;

/**
 * Represents create usage charge.
 */
final class UsageCharge extends AbstractTransfer
{
    /**
     * The shop ID.
     *
     * @var ShopId
     */
    public $shopId;

    /**
     * The plan ID.
     *
     * @var PlanId
     */
    public $planId;

    /**
     * The charge ID from Shopify.
     *
     * @var ChargeReference
     */
    public $chargeReference;

    /**
     * Usage charge type.
     *
     * @var ChargeType
     */
    public $chargeType;

    /**
     * Usage charge status.
     *
     * @var ChargeStatus
     */
    public $chargeStatus;

    /**
     * When the charge will be billed on.
     *
     * @var Carbon
     */
    public $billingOn;

    /**
     * Usage charge details.
     *
     * @var UsageChargeDetails
     */
    public $details;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->chargeType = ChargeType::USAGE();
        $this->chargeStatus = ChargeStatus::ACCEPTED();
    }
}
