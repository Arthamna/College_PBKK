<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Exceptions\InvalidTransitionException;
use App\Exceptions\ShopException;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Single place where order status changes. Every change is validated against
 * OrderStatus::allowedTransitions() and applied with an optimistic
 * "WHERE status = :current" update to avoid lost updates.
 */
class OrderService
{
    public function __construct(
        private readonly BaseConnection $db,
        private readonly OrderModel $orders,
        private readonly OrderItemModel $items,
        private readonly ProductModel $products,
    ) {
    }

    public function transition(int $orderId, OrderStatus $next): void
    {
        $this->db->transBegin();

        try {
            $current = $this->orders->statusOf($orderId)
                ?? throw new ShopException('Pesanan tidak ditemukan.');

            if (! $current->canTransitionTo($next)) {
                throw InvalidTransitionException::because(
                    sprintf('Pesanan berstatus "%s" tidak dapat diubah menjadi "%s".', $current->label(), $next->label()),
                );
            }

            $this->db->table('orders')
                ->where('id', $orderId)
                ->where('status', $current->value)
                ->update(['status' => $next->value, 'updated_at' => date('Y-m-d H:i:s')]);

            if ($this->db->affectedRows() !== 1) {
                throw InvalidTransitionException::because('Pesanan baru saja diubah oleh pengguna lain. Muat ulang halaman.');
            }

            if ($next === OrderStatus::Cancelled) {
                $this->releaseReservedStock($orderId);
            }

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    /**
     * A customer may only cancel their own order while it is still unpaid.
     */
    public function cancelByCustomer(int $orderId, int $userId): void
    {
        $order = $this->orders->findForUser($orderId, $userId)
            ?? throw new ShopException('Pesanan tidak ditemukan.');

        if ($order['status'] !== OrderStatus::PendingPayment->value) {
            throw InvalidTransitionException::because('Hanya pesanan yang belum dibayar yang dapat dibatalkan.');
        }

        $this->transition($orderId, OrderStatus::Cancelled);
    }

    private function releaseReservedStock(int $orderId): void
    {
        foreach ($this->items->forOrder($orderId) as $item) {
            $this->products->releaseStock((int) $item['product_id'], (int) $item['quantity']);
        }
    }
}
