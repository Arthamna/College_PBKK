<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\EmptyCartException;
use App\Exceptions\OutOfStockException;
use App\Exceptions\ShopException;
use App\Models\AddressModel;
use App\Models\CartItemModel;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Stock policy: stock is RESERVED (decremented) at checkout, inside the same
 * database transaction that creates the order. It is released again when the
 * order is cancelled (see OrderService). Verification of payment therefore never
 * has to compete for stock.
 */
class CheckoutService
{
    public function __construct(
        private readonly BaseConnection $db,
        private readonly CartService $cart,
        private readonly AddressModel $addresses,
        private readonly ProductModel $products,
        private readonly OrderModel $orders,
        private readonly OrderItemModel $orderItems,
        private readonly PaymentModel $payments,
        private readonly CartItemModel $cartItems,
    ) {
    }

    /**
     * @return int the new order id
     */
    public function place(int $userId, int $addressId, ?string $notes): int
    {
        $address = $this->addresses->findOwned($addressId, $userId)
            ?? throw new ShopException('Alamat pengiriman tidak valid.');

        $this->db->transBegin();

        try {
            // Re-read the cart inside the transaction: prices and totals come from the
            // database, never from the browser.
            $summary = $this->cart->summary($userId);

            if ($summary->isEmpty()) {
                throw EmptyCartException::because();
            }

            foreach ($summary->lines as $line) {
                if (! $this->products->reserveStock($line['product_id'], $line['quantity'])) {
                    throw OutOfStockException::because(
                        sprintf('Stok "%s" tidak mencukupi untuk %d unit.', $line['name'], $line['quantity']),
                    );
                }
            }

            $orderId = (int) $this->orders->insert([
                'order_number'     => $this->newOrderNumber(),
                'user_id'          => $userId,
                'subtotal'         => $summary->subtotal,
                'shipping_cost'    => $summary->shipping,
                'total'            => $summary->total(),
                'status'           => OrderStatus::PendingPayment->value,
                'address_snapshot' => json_encode($this->snapshot($address), JSON_THROW_ON_ERROR),
                'notes'            => $notes !== null && trim($notes) !== '' ? trim($notes) : null,
            ], true);

            foreach ($summary->lines as $line) {
                $this->orderItems->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $line['product_id'],
                    'product_name' => $line['name'],
                    'unit_price'   => $line['unit_price'],
                    'quantity'     => $line['quantity'],
                    'subtotal'     => $line['subtotal'],
                ]);
            }

            $this->payments->insert([
                'order_id' => $orderId,
                'amount'   => $summary->total(),
                'method'   => 'bank_transfer',
                'status'   => PaymentStatus::Unpaid->value,
            ]);

            $this->cartItems->where('cart_id', $this->cartIdOf($userId))->delete();

            $this->db->transCommit();

            return $orderId;
        } catch (ShopException | DatabaseException | \JsonException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    private function newOrderNumber(): string
    {
        return 'SE' . date('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    private function cartIdOf(int $userId): int
    {
        return (int) $this->db->table('carts')->select('id')->where('user_id', $userId)->get()->getRow('id');
    }

    /**
     * @param  array<string, mixed> $address
     * @return array<string, string>
     */
    private function snapshot(array $address): array
    {
        return [
            'recipient_name' => (string) $address['recipient_name'],
            'phone'          => (string) $address['phone'],
            'street'         => (string) $address['street'],
            'city'           => (string) $address['city'],
            'province'       => (string) $address['province'],
            'postal_code'    => (string) $address['postal_code'],
        ];
    }
}
