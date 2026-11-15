<?php

declare(strict_types=1);

namespace App\Enums;

enum ShipmentStatus: string
{
    case Shipped   = 'shipped';
    case Delivered = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::Shipped   => 'Dalam pengiriman',
            self::Delivered => 'Terkirim',
        };
    }
}
