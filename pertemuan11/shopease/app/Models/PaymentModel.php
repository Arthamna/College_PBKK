<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table         = 'payments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'order_id', 'amount', 'method', 'proof_file', 'status',
        'rejection_reason', 'verified_by', 'verified_at',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findByOrder(int $orderId): ?array
    {
        return $this->where('order_id', $orderId)->first();
    }
}
