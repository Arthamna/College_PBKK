<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table         = 'order_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $allowedFields = ['order_id', 'product_id', 'product_name', 'unit_price', 'quantity', 'subtotal'];

    /**
     * @return list<array<string, mixed>>
     */
    public function forOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)->orderBy('id')->findAll();
    }
}
