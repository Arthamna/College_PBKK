<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Exceptions\ShopException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use Config\Shop;

class ShipmentController extends BaseController
{
    public function ship(int $orderId): RedirectResponse
    {
        $couriers = implode(',', config(Shop::class)->couriers);

        if (! $this->validate([
            'courier'         => 'required|in_list[' . $couriers . ']',
            'tracking_number' => 'required|min_length[5]|max_length[60]|alpha_numeric_punct',
        ])) {
            return $this->back('admin/orders/' . $orderId, null, implode(' ', $this->validationErrors()));
        }

        try {
            Services::shipments()->ship(
                $orderId,
                (string) $this->request->getPost('courier'),
                (string) $this->request->getPost('tracking_number'),
            );
        } catch (ShopException $e) {
            return $this->failWith('admin/orders/' . $orderId, $e);
        }

        return $this->back('admin/orders/' . $orderId, 'Pesanan ditandai dikirim.');
    }

    public function delivered(int $orderId): RedirectResponse
    {
        try {
            Services::shipments()->markDelivered($orderId);
        } catch (ShopException $e) {
            return $this->failWith('admin/orders/' . $orderId, $e);
        }

        return $this->back('admin/orders/' . $orderId, 'Pesanan selesai.');
    }
}
