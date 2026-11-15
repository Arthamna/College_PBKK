<?php

declare(strict_types=1);

namespace App\Exceptions;

class OutOfStockException extends ShopException
{
    public static function because(string $reason = 'Stok tidak mencukupi.'): self
    {
        return new self($reason);
    }
}
