<?php

namespace Segwitz\ShopifyApp\Http\Controllers;

use Illuminate\Routing\Controller;
use Segwitz\ShopifyApp\Traits\AuthController as AuthControllerTrait;

/**
 * Responsible for authenticating the shop.
 */
class AuthController extends Controller
{
    use AuthControllerTrait;
}
