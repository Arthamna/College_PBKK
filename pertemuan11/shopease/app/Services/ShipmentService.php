<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Exceptions\InvalidTransitionException;
use App\Exceptions\ShopException;
use App\Models\OrderModel;
use App\Models\ShipmentModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ShipmentService
{
    public function __construct(
        private readonly BaseConnection $db,
        private readonly OrderService $orderService,
        private readonly OrderModel $orders,
        private readonly ShipmentModel $shipments,
    ) {
    }

    public function ship(int $orderId, string $courier, string $trackingNumber): void
    {
        if ($this->orders->statusOf($orderId) !== OrderStatus::Processing) {
            throw InvalidTransitionException::because('Hanya pesanan berstatus "Diproses" yang dapat dikirim.');
        }

        $this->db->transBegin();

        try {
            $saved = $this->shipments->insert([
                'order_id'        => $orderId,
                'courier'         => trim($courier),
                'tracking_number' => trim($trackingNumber),
                'status'          => ShipmentStatus::Shipped->value,
                'shipped_at'      => date('Y-m-d H:i:s'),
            ]);

            if ($saved === false) {
                throw new ShopException(implode(' ', $this->shipments->errors()));
            }

            $this->orderService->transition($orderId, OrderStatus::Shipped);

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    public function markDelivered(int $orderId): void
    {
        $shipment = $this->shipments->findByOrder($orderId)
            ?? throw new ShopException('Data pengiriman tidak ditemukan.');

        $this->db->transBegin();

        try {
            $this->orderService->transition($orderId, OrderStatus::Completed);
            $this->shipments->update((int) $shipment['id'], [
                'status'       => ShipmentStatus::Delivered->value,
                'delivered_at' => date('Y-m-d H:i:s'),
            ]);

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }
}
