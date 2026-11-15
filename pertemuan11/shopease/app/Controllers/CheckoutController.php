<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ShopException;
use App\Models\AddressModel;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class CheckoutController extends BaseController
{
    /**
     * @return RedirectResponse|string
     */
    public function index()
    {
        $user = $this->user();
        $cart = Services::cart()->summary($user['id']);

        if ($cart->isEmpty()) {
            return $this->back('cart', null, 'Keranjang Anda kosong.');
        }

        return view('shop/checkout', [
            'cart'      => $cart,
            'addresses' => model(AddressModel::class)->forUser($user['id']),
        ]);
    }

    public function place(): RedirectResponse
    {
        if (! $this->validate(['address_id' => 'required|is_natural_no_zero', 'notes' => 'permit_empty|max_length[500]'])) {
            return $this->back('checkout', null, 'Pilih alamat pengiriman.');
        }

        try {
            $orderId = Services::checkout()->place(
                $this->user()['id'],
                (int) $this->request->getPost('address_id'),
                $this->request->getPost('notes') !== null ? (string) $this->request->getPost('notes') : null,
            );
        } catch (ShopException $e) {
            return $this->failWith('checkout', $e);
        }

        return $this->back('orders/' . $orderId, 'Pesanan dibuat. Silakan unggah bukti pembayaran.');
    }

    public function storeAddress(): RedirectResponse
    {
        $addresses = model(AddressModel::class);

        $data = [
            'user_id'        => $this->user()['id'],
            'label'          => trim((string) $this->request->getPost('label')),
            'recipient_name' => trim((string) $this->request->getPost('recipient_name')),
            'phone'          => trim((string) $this->request->getPost('phone')),
            'street'         => trim((string) $this->request->getPost('street')),
            'city'           => trim((string) $this->request->getPost('city')),
            'province'       => trim((string) $this->request->getPost('province')),
            'postal_code'    => trim((string) $this->request->getPost('postal_code')),
        ];

        if (! $addresses->insert($data)) {
            return redirect()->to(site_url('checkout'))->withInput()->with('errors', $addresses->errors());
        }

        return $this->back('checkout', 'Alamat disimpan.');
    }
}
