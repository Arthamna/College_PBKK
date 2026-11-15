<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid              = 'unpaid';
    case WaitingVerification = 'waiting_verification';
    case Verified            = 'verified';
    case Rejected            = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid              => 'Belum dibayar',
            self::WaitingVerification => 'Menunggu verifikasi',
            self::Verified            => 'Terverifikasi',
            self::Rejected            => 'Ditolak',
        };
    }

    public function canUploadProof(): bool
    {
        return $this === self::Unpaid || $this === self::Rejected;
    }
}
