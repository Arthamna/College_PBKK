<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Exceptions\OutOfStockException;
use App\Exceptions\ShopException;
use Config\Services;
use Tests\Support\ShopTestCase;

final class CartTest extends ShopTestCase
{
    public function testAddingStoresItemAndQuantity(): void
    {
        $cart = Services::cart();
        $cart->add($this->customerId(), $this->productId('php-8-modern-pemula'), 2);
        $cart->add($this->customerId(), $this->productId('php-8-modern-pemula'), 1);

        $summary = $cart->summary($this->customerId());

        $this->assertCount(1, $summary->lines);
        $this->assertSame(3, $summary->lines[0]['quantity']);
        $this->assertSame(75000 * 3, $summary->subtotal);
        $this->assertSame(75000 * 3 + 15000, $summary->total());
    }

    public function testQuantityMustBePositive(): void
    {
        $this->expectException(ShopException::class);

        Services::cart()->add($this->customerId(), $this->productId('php-8-modern-pemula'), 0);
    }

    public function testQuantityCannotExceedStock(): void
    {
        $this->expectException(OutOfStockException::class);

        Services::cart()->add($this->customerId(), $this->productId('paket-ikon-vektor'), 4); // stock = 3
    }

    public function testOutOfStockProductCannotBeAdded(): void
    {
        $this->expectException(OutOfStockException::class);

        Services::cart()->add($this->customerId(), $this->productId('kelas-keamanan-web'), 1); // stock = 0
    }

    public function testInactiveProductCannotBeAdded(): void
    {
        $id = $this->productId('php-8-modern-pemula');
        $this->db->table('products')->where('id', $id)->update(['status' => 'inactive']);

        $this->expectException(ShopException::class);

        Services::cart()->add($this->customerId(), $id, 1);
    }

    public function testUpdateAndRemove(): void
    {
        $cart      = Services::cart();
        $productId = $this->productId('php-8-modern-pemula');

        $cart->add($this->customerId(), $productId, 1);
        $cart->setQuantity($this->customerId(), $productId, 5);
        $this->assertSame(5, $cart->summary($this->customerId())->lines[0]['quantity']);

        $cart->remove($this->customerId(), $productId);
        $this->assertTrue($cart->summary($this->customerId())->isEmpty());
    }
}
