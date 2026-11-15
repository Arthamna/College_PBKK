<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Shop extends BaseConfig
{
    public const string STORE_NAME = 'ShopEase';

    /** Items per catalog page. */
    public int $perPage = 12;

    /** Flat shipping fee in whole rupiah. */
    public int $shippingCost = 15000;

    /** Maximum upload size in kilobytes (product photo and payment proof). */
    public int $maxUploadKb = 2048;

    /**
     * Allowed upload types: extension => accepted MIME types.
     * The MIME type is detected from file content (finfo), never from the client.
     *
     * @var array<string, list<string>>
     */
    public array $allowedImages = [
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'webp' => ['image/webp'],
    ];

    /** Bank account shown to customers on the payment page. */
    public string $bankName = 'Bank ShopEase';

    public string $bankAccount = '1234567890 a.n. ShopEase Digital';

    /** @var list<string> */
    public array $couriers = ['JNE', 'J&T', 'SiCepat', 'Pos Indonesia'];
}
