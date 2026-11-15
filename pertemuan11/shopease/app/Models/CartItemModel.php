<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductStatus;
use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table         = 'cart_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['cart_id', 'product_id', 'quantity'];

    /**
     * @return list<array<string, mixed>>
     */
    public function linesForCart(int $cartId): array
    {
        return $this->select('cart_items.product_id, cart_items.quantity, products.name, products.slug,'
                . ' products.price, products.stock, products.image, products.status')
            ->join('products', 'products.id = cart_items.product_id')
            ->where('cart_items.cart_id', $cartId)
            ->where('products.status', ProductStatus::Active->value)
            ->orderBy('cart_items.id')
            ->findAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findLine(int $cartId, int $productId): ?array
    {
        return $this->where('cart_id', $cartId)->where('product_id', $productId)->first();
    }

    public function clearCart(int $cartId): void
    {
        $this->where('cart_id', $cartId)->delete();
    }
}
