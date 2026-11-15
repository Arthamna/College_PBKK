<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Database\Seeds\DatabaseSeeder;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Services;

/**
 * Fresh seeded database (in-memory SQLite) for every test.
 */
abstract class ShopTestCase extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';
    protected $seed        = DatabaseSeeder::class;
    protected $seedOnce    = false;

    protected function customerId(): int
    {
        return (int) $this->db->table('users')->where('email', 'customer@shopease.test')->get()->getRow('id');
    }

    protected function staffId(string $email = 'admin@shopease.test'): int
    {
        return (int) $this->db->table('users')->where('email', $email)->get()->getRow('id');
    }

    protected function productId(string $slug): int
    {
        return (int) $this->db->table('products')->where('slug', $slug)->get()->getRow('id');
    }

    protected function stockOf(int $productId): int
    {
        return (int) model(ProductModel::class)->find($productId)['stock'];
    }

    protected function addressId(): int
    {
        return (int) $this->db->table('addresses')->where('user_id', $this->customerId())->get()->getRow('id');
    }

    /**
     * Creates an order for the seeded customer and returns its id.
     */
    protected function placeOrder(string $slug = 'panduan-codeigniter-4', int $quantity = 2): int
    {
        Services::cart()->add($this->customerId(), $this->productId($slug), $quantity);

        return Services::checkout()->place($this->customerId(), $this->addressId(), null);
    }
}
