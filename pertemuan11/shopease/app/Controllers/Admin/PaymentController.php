<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Exceptions\ShopException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

/**
 * Payment verification is restricted to administrators by the route filter.
 */
class PaymentController extends BaseController
{
    public function verify(int $orderId): RedirectResponse
    {
        try {
            Services::payments()->verify($this->user()['id'], $orderId);
        } catch (ShopException $e) {
            return $this->failWith('admin/orders/' . $orderId, $e);
        }

        return $this->back('admin/orders/' . $orderId, 'Pembayaran diverifikasi. Pesanan masuk proses.');
    }

    public function reject(int $orderId): RedirectResponse
    {
        if (! $this->validate(['reason' => 'required|min_length[3]|max_length[255]'])) {
            return $this->back('admin/orders/' . $orderId, null, 'Alasan penolakan wajib diisi.');
        }

        try {
            Services::payments()->reject($this->user()['id'], $orderId, (string) $this->request->getPost('reason'));
        } catch (ShopException $e) {
            return $this->failWith('admin/orders/' . $orderId, $e);
        }

        return $this->back('admin/orders/' . $orderId, 'Pembayaran ditolak. Customer dapat mengunggah ulang.');
    }
}
