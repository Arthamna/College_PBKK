<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ShipmentModel extends Model
{
    protected $table         = 'shipments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['order_id', 'courier', 'tracking_number', 'status', 'shipped_at', 'delivered_at'];

    /** @var array<string, string> */
    protected $validationRules = [
        'courier'         => 'required|max_length[50]',
        'tracking_number' => 'required|min_length[5]|max_length[60]|alpha_numeric_punct',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findByOrder(int $orderId): ?array
    {
        return $this->where('order_id', $orderId)->first();
    }
}
