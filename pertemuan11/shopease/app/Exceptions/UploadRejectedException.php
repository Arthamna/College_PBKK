<?php

declare(strict_types=1);

namespace App\Exceptions;

class UploadRejectedException extends ShopException
{
    public static function because(string $reason = 'Berkas ditolak.'): self
    {
        return new self($reason);
    }
}
