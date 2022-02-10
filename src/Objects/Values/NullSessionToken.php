<?php

namespace Segwitz\ShopifyApp\Objects\Values;

use Funeralzone\ValueObjects\NullTrait;
use Segwitz\ShopifyApp\Contracts\Objects\Values\SessionToken as SessionTokenValue;

/**
 * Value object for session token (null).
 */
final class NullSessionToken implements SessionTokenValue
{
    use NullTrait;
}
