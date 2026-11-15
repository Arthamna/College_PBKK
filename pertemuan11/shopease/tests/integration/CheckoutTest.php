<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\EmptyCartException;
use App\Exceptions\OutOfStockException;
use App\Exceptions\ShopException;
use Config\Services;
use Tests\Support\ShopTestCase;

final class CheckoutTest extends ShopTestCase
{
    public function testCheckoutCreatesOrderItemsPaymentAndReservesStock(): void
    {
        $productId   = $this->productId('panduan-codeigniter-4');
        $stockBefore = $this->stockOf($productId);

        $orderId = $this->placeOrder('panduan-codeigniter-4', 2);

        $order = $this->db->table('orders')->where('id', $orderId)->get()->getRowArray();
        $this->assertSame(OrderStatus::PendingPayment->value, $order['status']);
        $this->assertEquals(89000 * 2 + 15000, (float) $order['total']);

        $items = $this->db->table('order_items')->where('order_id', $orderId)->get()->getResultArray();
        $this->assertCount(1, $items);
        $this->assertEquals(89000, (float) $items[0]['unit_price']);

        $payment = $this->db->table('payments')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertSame(PaymentStatus::Unpaid->value, $payment['status']);

        $this->assertSame($stockBefore - 2, $this->stockOf($productId));
        $this->assertTrue(Services::cart()->summary($this->customerId())->isEmpty());
    }

    public function testCheckoutBeyondStockIsRejectedWithoutChangingStock(): void
    {
        $productId = $this->productId('paket-ikon-vektor'); // stock 3
        Services::cart()->add($this->customerId(), $productId, 3);

        // Someone else buys meanwhile: stock drops below the cart quantity.
        $this->db->table('products')->where('id', $productId)->update(['stock' => 1]);

        try {
            Services::checkout()->place($this->customerId(), $this->addressId(), null);
            $this->fail('Expected OutOfStockException');
        } catch (OutOfStockException) {
            $this->assertSame(1, $this->stockOf($productId));
            $this->assertSame(0, $this->db->table('orders')->countAllResults());
            $this->assertFalse(Services::cart()->summary($this->customerId())->isEmpty());
        }
    }

    public function testPartialFailureRollsBackEarlierReservations(): void
    {
        $first  = $this->productId('panduan-codeigniter-4');
        $second = $this->productId('paket-ikon-vektor');
        $before = $this->stockOf($first);

        Services::cart()->add($this->customerId(), $first, 1);
        Services::cart()->add($this->customerId(), $second, 2);
        $this->db->table('products')->where('id', $second)->update(['stock' => 0]);

        try {
            Services::checkout()->place($this->customerId(), $this->addressId(), null);
            $this->fail('Expected OutOfStockException');
        } catch (OutOfStockException) {
            $this->assertSame($before, $this->stockOf($first), 'first reservation must be rolled back');
        }
    }

    public function testEmptyCartCannotCheckout(): void
    {
        $this->expectException(EmptyCartException::class);

        Services::checkout()->place($this->customerId(), $this->addressId(), null);
    }

    public function testAddressOfAnotherUserIsRejected(): void
    {
        $other = Services::auth()->register('Orang Lain', 'lain@example.com', 'rahasia-panjang', null);
        $this->db->table('addresses')->insert([
            'user_id' => $other, 'label' => 'X', 'recipient_name' => 'X', 'phone' => '0811', 'street' => 'Jl. X 1',
            'city' => 'X', 'province' => 'X', 'postal_code' => '12345',
        ]);
        $foreignAddress = (int) $this->db->insertID();

        Services::cart()->add($this->customerId(), $this->productId('panduan-codeigniter-4'), 1);

        $this->expectException(ShopException::class);

        Services::checkout()->place($this->customerId(), $foreignAddress, null);
    }

    public function testOrderItemPriceIsASnapshot(): void
    {
        $orderId = $this->placeOrder('panduan-codeigniter-4', 1);

        $this->db->table('products')->where('id', $this->productId('panduan-codeigniter-4'))->update(['price' => 1]);

        $item = $this->db->table('order_items')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertEquals(89000, (float) $item['unit_price']);
    }
}
