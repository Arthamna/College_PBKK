<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case PendingPayment     = 'pending_payment';
    case WaitingVerification = 'waiting_verification';
    case Processing         = 'processing';
    case Shipped            = 'shipped';
    case Completed          = 'completed';
    case Cancelled          = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PendingPayment      => 'Menunggu pembayaran',
            self::WaitingVerification => 'Menunggu verifikasi',
            self::Processing          => 'Diproses',
            self::Shipped             => 'Dikirim',
            self::Completed           => 'Selesai',
            self::Cancelled           => 'Dibatalkan',
        };
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PendingPayment      => [self::WaitingVerification, self::Cancelled],
            self::WaitingVerification => [self::Processing, self::PendingPayment, self::Cancelled],
            self::Processing          => [self::Shipped, self::Cancelled],
            self::Shipped             => [self::Completed],
            self::Completed, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    /** Whether orders in this status count as realised sales. */
    public function isSale(): bool
    {
        return match ($this) {
            self::Processing, self::Shipped, self::Completed => true,
            default => false,
        };
    }

    /**
     * @return list<string>
     */
    public static function saleValues(): array
    {
        return array_map(
            static fn (self $status): string => $status->value,
            array_filter(self::cases(), static fn (self $status): bool => $status->isSale()),
        );
    }
}
