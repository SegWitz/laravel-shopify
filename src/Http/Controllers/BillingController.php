<?php

namespace Segwitz\ShopifyApp\Http\Controllers;

use Illuminate\Routing\Controller;
use Segwitz\ShopifyApp\Traits\BillingController as BillingControllerTrait;

/**
 * Responsible for billing a shop for plans and usage charges.
 */
class BillingController extends Controller
{
    use BillingControllerTrait;
}
