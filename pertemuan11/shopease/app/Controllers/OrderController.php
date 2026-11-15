<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\ShopException;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ShipmentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use Config\Shop;

class OrderController extends BaseController
{
    public function index(): string
    {
        $orders = model(OrderModel::class)
            ->where('user_id', $this->user()['id'])
            ->orderBy('created_at', 'DESC');

        return view('shop/orders', ['orders' => $orders->paginate(10), 'pager' => $orders->pager]);
    }

    public function show(int $id): string
    {
        $order = model(OrderModel::class)->findForUser($id, $this->user()['id'])
            ?? throw PageNotFoundException::forPageNotFound();

        return view('shop/order', [
            'order'    => $order,
            'status'   => OrderStatus::from($order['status']),
            'items'    => model(OrderItemModel::class)->forOrder($id),
            'payment'  => model(PaymentModel::class)->findByOrder($id),
            'shipment' => model(ShipmentModel::class)->findByOrder($id),
            'address'  => json_decode((string) $order['address_snapshot'], true, 512, JSON_THROW_ON_ERROR),
            'shop'     => config(Shop::class),
            'paymentStatuses' => PaymentStatus::class,
        ]);
    }

    public function uploadProof(int $id): RedirectResponse
    {
        $file = $this->request->getFile('proof');

        if ($file === null) {
            return $this->back('orders/' . $id, null, 'Pilih berkas bukti pembayaran.');
        }

        try {
            Services::payments()->submitProof($this->user()['id'], $id, $file);
        } catch (ShopException $e) {
            return $this->failWith('orders/' . $id, $e);
        }

        return $this->back('orders/' . $id, 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            Services::orders()->cancelByCustomer($id, $this->user()['id']);
        } catch (ShopException $e) {
            return $this->failWith('orders/' . $id, $e);
        }

        return $this->back('orders/' . $id, 'Pesanan dibatalkan.');
    }
}
