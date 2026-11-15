<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\CartSummary;
use App\Enums\ProductStatus;
use App\Exceptions\OutOfStockException;
use App\Exceptions\ShopException;
use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;
use Config\Shop;

class CartService
{
    public function __construct(
        private readonly CartModel $carts,
        private readonly CartItemModel $items,
        private readonly ProductModel $products,
        private readonly Shop $config,
    ) {
    }

    public function summary(int $userId): CartSummary
    {
        $cartId = $this->carts->idForUser($userId);
        $lines  = [];
        $sum    = 0;

        foreach ($this->items->linesForCart($cartId) as $row) {
            $unitPrice = (int) round((float) $row['price']);
            $quantity  = (int) $row['quantity'];
            $subtotal  = $unitPrice * $quantity;
            $sum      += $subtotal;

            $lines[] = [
                'product_id' => (int) $row['product_id'],
                'name'       => (string) $row['name'],
                'slug'       => (string) $row['slug'],
                'image'      => $row['image'] !== null ? (string) $row['image'] : null,
                'unit_price' => $unitPrice,
                'quantity'   => $quantity,
                'stock'      => (int) $row['stock'],
                'subtotal'   => $subtotal,
            ];
        }

        return new CartSummary($lines, $sum, $lines === [] ? 0 : $this->config->shippingCost);
    }

    public function add(int $userId, int $productId, int $quantity): void
    {
        $product = $this->activeProduct($productId);
        $cartId  = $this->carts->idForUser($userId);
        $line    = $this->items->findLine($cartId, $productId);
        $newQty  = $quantity + ($line !== null ? (int) $line['quantity'] : 0);

        $this->assertQuantity($newQty, $product);

        if ($line === null) {
            $this->items->insert(['cart_id' => $cartId, 'product_id' => $productId, 'quantity' => $newQty]);

            return;
        }

        $this->items->update((int) $line['id'], ['quantity' => $newQty]);
    }

    public function setQuantity(int $userId, int $productId, int $quantity): void
    {
        $product = $this->activeProduct($productId);
        $cartId  = $this->carts->idForUser($userId);
        $line    = $this->items->findLine($cartId, $productId)
            ?? throw new ShopException('Produk tidak ada di keranjang.');

        $this->assertQuantity($quantity, $product);
        $this->items->update((int) $line['id'], ['quantity' => $quantity]);
    }

    public function remove(int $userId, int $productId): void
    {
        $cartId = $this->carts->idForUser($userId);
        $this->items->where('cart_id', $cartId)->where('product_id', $productId)->delete();
    }

    /**
     * @return array<string, mixed>
     */
    private function activeProduct(int $productId): array
    {
        $product = $this->products->find($productId);

        if ($product === null || $product['status'] !== ProductStatus::Active->value) {
            throw new ShopException('Produk tidak tersedia.');
        }

        return $product;
    }

    /**
     * @param array<string, mixed> $product
     */
    private function assertQuantity(int $quantity, array $product): void
    {
        if ($quantity < 1) {
            throw new ShopException('Kuantitas harus lebih dari nol.');
        }

        if ($quantity > (int) $product['stock']) {
            throw OutOfStockException::because(
                sprintf('Stok "%s" hanya tersisa %d.', $product['name'], (int) $product['stock']),
            );
        }
    }
}
