<?php

namespace Segwitz\ShopifyApp\Objects\Values;

use Funeralzone\ValueObjects\NullTrait;
use Segwitz\ShopifyApp\Contracts\Objects\Values\AccessToken as AccessTokenValue;

/**
 * Value object for access token (null).
 */
final class NullAccessToken implements AccessTokenValue
{
    use NullTrait;

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return true;
    }
}
