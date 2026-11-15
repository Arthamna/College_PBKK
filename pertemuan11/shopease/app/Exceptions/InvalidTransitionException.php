<?php

declare(strict_types=1);

namespace App\Exceptions;

class InvalidTransitionException extends ShopException
{
    public static function because(string $reason = 'Perubahan status tidak diizinkan.'): self
    {
        return new self($reason);
    }
}
