<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use CodeIgniter\Model;

class ProductModel extends Model
{
    public const array SORTS = [
        'newest'     => ['products.created_at', 'DESC'],
        'price_asc'  => ['products.price', 'ASC'],
        'price_desc' => ['products.price', 'DESC'],
        'name'       => ['products.name', 'ASC'],
    ];

    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'status'];

    /** @var array<string, string> */
    protected $validationRules = [
        'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
        'name'        => 'required|min_length[3]|max_length[150]',
        'description' => 'permit_empty|max_length[5000]',
        'price'       => 'required|is_natural',
        'stock'       => 'required|is_natural',
        'status'      => 'required|in_list[active,inactive]',
    ];

    /**
     * Applies catalog filters and returns $this so the caller can paginate().
     */
    public function catalog(?string $search, ?int $categoryId, string $sort, bool $activeOnly = true): static
    {
        $this->select('products.*, categories.name AS category_name, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id');

        if ($activeOnly) {
            $this->where('products.status', ProductStatus::Active->value);
        }

        if ($search !== null && $search !== '') {
            $this->groupStart()
                ->like('products.name', $search)
                ->orLike('products.description', $search)
                ->groupEnd();
        }

        if ($categoryId !== null) {
            $this->where('products.category_id', $categoryId);
        }

        [$column, $direction] = self::SORTS[$sort] ?? self::SORTS['newest'];

        return $this->orderBy($column, $direction);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findActiveBySlug(string $slug): ?array
    {
        return $this->select('products.*, categories.name AS category_name, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.slug', $slug)
            ->where('products.status', ProductStatus::Active->value)
            ->first();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function latest(int $limit): array
    {
        return $this->catalog(null, null, 'newest')->findAll($limit);
    }

    /**
     * Best sellers by units sold in paid (non-cancelled, non-pending) orders.
     *
     * @return list<array<string, mixed>>
     */
    public function bestSellers(int $limit): array
    {
        return $this->select('products.*, categories.name AS category_name, SUM(order_items.quantity) AS sold')
            ->join('categories', 'categories.id = products.category_id')
            ->join('order_items', 'order_items.product_id = products.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('products.status', ProductStatus::Active->value)
            ->whereIn('orders.status', OrderStatus::saleValues())
            ->groupBy('products.id')
            ->orderBy('sold', 'DESC')
            ->findAll($limit);
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $builder = $this->where('slug', $slug);

        if ($exceptId !== null) {
            $builder->where('id !=', $exceptId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Atomically reserves stock with a single conditional UPDATE, so concurrent
     * checkouts can never push stock below zero.
     */
    public function reserveStock(int $productId, int $quantity): bool
    {
        $this->builder()
            ->set('stock', 'stock - ' . $quantity, false)
            ->where('id', $productId)
            ->where('status', ProductStatus::Active->value)
            ->where('stock >=', $quantity)
            ->update();

        return $this->db->affectedRows() === 1;
    }

    public function releaseStock(int $productId, int $quantity): void
    {
        $this->builder()
            ->set('stock', 'stock + ' . $quantity, false)
            ->where('id', $productId)
            ->update();
    }

    public function hasOrders(int $productId): bool
    {
        return $this->db->table('order_items')->where('product_id', $productId)->countAllResults() > 0;
    }
}
