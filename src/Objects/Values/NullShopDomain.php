<?php

namespace Segwitz\ShopifyApp\Objects\Values;

use Funeralzone\ValueObjects\NullTrait;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopDomain as ShopDomainValue;

/**
 * Value object for the shop's domain (null).
 */
final class NullShopDomain implements ShopDomainValue
{
    use NullTrait;
}
