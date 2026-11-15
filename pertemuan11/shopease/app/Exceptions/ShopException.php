<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Base class for business-rule violations. The message is safe to show to users.
 */
class ShopException extends RuntimeException
{
}
