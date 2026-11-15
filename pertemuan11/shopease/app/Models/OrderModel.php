<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'order_number', 'user_id', 'subtotal', 'shipping_cost', 'total',
        'status', 'address_snapshot', 'notes',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findForUser(int $orderId, int $userId): ?array
    {
        return $this->where('id', $orderId)->where('user_id', $userId)->first();
    }

    /**
     * Staff view: order joined with customer and payment/shipment state.
     *
     * @return array<string, mixed>|null
     */
    public function findDetailed(int $orderId): ?array
    {
        return $this->select('orders.*, users.name AS customer_name, users.email AS customer_email,'
                . ' payments.status AS payment_status, payments.proof_file, payments.rejection_reason')
            ->join('users', 'users.id = orders.user_id')
            ->join('payments', 'payments.order_id = orders.id', 'left')
            ->where('orders.id', $orderId)
            ->first();
    }

    public function listForStaff(?OrderStatus $status): static
    {
        $this->select('orders.*, users.name AS customer_name, payments.status AS payment_status')
            ->join('users', 'users.id = orders.user_id')
            ->join('payments', 'payments.order_id = orders.id', 'left')
            ->orderBy('orders.created_at', 'DESC');

        if ($status !== null) {
            $this->where('orders.status', $status->value);
        }

        return $this;
    }

    public function statusOf(int $orderId): ?OrderStatus
    {
        $row = $this->select('status')->find($orderId);

        return $row === null ? null : OrderStatus::from($row['status']);
    }
}
