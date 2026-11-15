<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case Administrator = 'administrator';
    case Operator      = 'operator';
    case Customer      = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Operator      => 'Operator Toko',
            self::Customer      => 'Customer',
        };
    }

    public function isStaff(): bool
    {
        return $this !== self::Customer;
    }
}
