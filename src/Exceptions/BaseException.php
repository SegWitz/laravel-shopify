<?php

namespace Segwitz\ShopifyApp\Exceptions;

use Exception;

/**
 * Base exception for all exceptions of the package.
 * Mainly to handle render in production.
 */
abstract class BaseException extends Exception
{
}
