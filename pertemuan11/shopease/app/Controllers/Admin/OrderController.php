<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Exceptions\ShopException;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\ShipmentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use Config\Shop;

class OrderController extends BaseController
{
    public function index(): string
    {
        $status = OrderStatus::tryFrom((string) $this->request->getGet('status'));
        $orders = model(OrderModel::class)->listForStaff($status);

        return view('admin/orders/index', [
            'orders'  => $orders->paginate(15),
            'pager'   => $orders->pager,
            'current' => $status,
        ]);
    }

    public function show(int $id): string
    {
        $order = model(OrderModel::class)->findDetailed($id)
            ?? throw PageNotFoundException::forPageNotFound();

        return view('admin/orders/show', [
            'order'         => $order,
            'status'        => OrderStatus::from($order['status']),
            'paymentStatus' => PaymentStatus::from($order['payment_status'] ?? PaymentStatus::Unpaid->value),
            'items'         => model(OrderItemModel::class)->forOrder($id),
            'shipment'      => model(ShipmentModel::class)->findByOrder($id),
            'address'       => json_decode((string) $order['address_snapshot'], true, 512, JSON_THROW_ON_ERROR),
            'couriers'      => config(Shop::class)->couriers,
            'isAdmin'       => $this->user()['role'] === Role::Administrator,
        ]);
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            Services::orders()->transition($id, OrderStatus::Cancelled);
        } catch (ShopException $e) {
            return $this->failWith('admin/orders/' . $id, $e);
        }

        return $this->back('admin/orders/' . $id, 'Pesanan dibatalkan dan stok dikembalikan.');
    }
}
