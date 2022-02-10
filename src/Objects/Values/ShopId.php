<?php

namespace Segwitz\ShopifyApp\Objects\Values;

use Funeralzone\ValueObjects\Scalars\IntegerTrait;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopId as ShopIdValue;

/**
 * Value object for shop's ID.
 */
final class ShopId implements ShopIdValue
{
    use IntegerTrait;
}
