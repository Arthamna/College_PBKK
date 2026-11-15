<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ShopException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class CartController extends BaseController
{
    public function index(): string
    {
        return view('shop/cart', ['cart' => Services::cart()->summary($this->user()['id'])]);
    }

    public function add(int $productId): RedirectResponse
    {
        $quantity = max(1, (int) $this->request->getPost('quantity'));

        try {
            Services::cart()->add($this->user()['id'], $productId, $quantity);
        } catch (ShopException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return $this->back('cart', 'Produk ditambahkan ke keranjang.');
    }

    public function update(int $productId): RedirectResponse
    {
        try {
            Services::cart()->setQuantity($this->user()['id'], $productId, (int) $this->request->getPost('quantity'));
        } catch (ShopException $e) {
            return $this->failWith('cart', $e);
        }

        return $this->back('cart', 'Keranjang diperbarui.');
    }

    public function remove(int $productId): RedirectResponse
    {
        Services::cart()->remove($this->user()['id'], $productId);

        return $this->back('cart', 'Produk dihapus dari keranjang.');
    }
}
