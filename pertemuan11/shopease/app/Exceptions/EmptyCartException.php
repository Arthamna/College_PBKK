<?php

declare(strict_types=1);

namespace App\Exceptions;

class EmptyCartException extends ShopException
{
    public static function because(string $reason = 'Keranjang kosong.'): self
    {
        return new self($reason);
    }
}
