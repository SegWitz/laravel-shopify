<?php

namespace Segwitz\ShopifyApp\Objects\Values;

use Funeralzone\ValueObjects\NullTrait;
use Segwitz\ShopifyApp\Contracts\Objects\Values\SessionId as SessionIdValue;

/**
 * Value object for session ID of a session token (null).
 */
final class NullSessionId implements SessionIdValue
{
    use NullTrait;
}
