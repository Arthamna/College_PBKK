<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\InvalidTransitionException;
use App\Exceptions\ShopException;
use App\Services\PaymentService;
use App\Services\UploadService;
use CodeIgniter\HTTP\Files\UploadedFile;
use Config\Services;
use Config\Shop;
use Tests\Support\ShopTestCase;

final class OrderLifecycleTest extends ShopTestCase
{
    /** Marks the payment as if the customer had uploaded a proof (file upload is covered by the HTTP smoke test). */
    private function submitProofDirectly(int $orderId): void
    {
        $this->db->table('payments')->where('order_id', $orderId)->update([
            'proof_file' => str_repeat('a', 32) . '.png',
            'status'     => PaymentStatus::WaitingVerification->value,
        ]);
        $this->db->table('orders')->where('id', $orderId)->update(['status' => OrderStatus::WaitingVerification->value]);
    }

    private function statusOf(int $orderId): string
    {
        return (string) $this->db->table('orders')->where('id', $orderId)->get()->getRow('status');
    }

    public function testVerificationMovesPaymentAndOrderForward(): void
    {
        $orderId = $this->placeOrder();
        $this->submitProofDirectly($orderId);

        Services::payments()->verify($this->staffId(), $orderId);

        $payment = $this->db->table('payments')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertSame(PaymentStatus::Verified->value, $payment['status']);
        $this->assertSame($this->staffId(), (int) $payment['verified_by']);
        $this->assertSame(OrderStatus::Processing->value, $this->statusOf($orderId));
    }

    public function testCannotVerifyUnpaidOrder(): void
    {
        $orderId = $this->placeOrder();

        $this->expectException(InvalidTransitionException::class);

        Services::payments()->verify($this->staffId(), $orderId);
    }

    public function testRejectionReturnsOrderToPendingPaymentAndAllowsReupload(): void
    {
        $orderId = $this->placeOrder();
        $this->submitProofDirectly($orderId);

        Services::payments()->reject($this->staffId(), $orderId, 'Nominal tidak sesuai');

        $payment = $this->db->table('payments')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertSame(PaymentStatus::Rejected->value, $payment['status']);
        $this->assertSame('Nominal tidak sesuai', $payment['rejection_reason']);
        $this->assertSame(OrderStatus::PendingPayment->value, $this->statusOf($orderId));
        $this->assertTrue(PaymentStatus::from($payment['status'])->canUploadProof());
    }

    public function testFullHappyPathToCompleted(): void
    {
        $orderId = $this->placeOrder();
        $this->submitProofDirectly($orderId);

        Services::payments()->verify($this->staffId(), $orderId);
        Services::shipments()->ship($orderId, 'JNE', 'JNE1234567890');
        $this->assertSame(OrderStatus::Shipped->value, $this->statusOf($orderId));

        Services::shipments()->markDelivered($orderId);
        $this->assertSame(OrderStatus::Completed->value, $this->statusOf($orderId));

        $shipment = $this->db->table('shipments')->where('order_id', $orderId)->get()->getRowArray();
        $this->assertSame('delivered', $shipment['status']);
        $this->assertNotNull($shipment['delivered_at']);
    }

    public function testCannotShipBeforePaymentIsVerified(): void
    {
        $orderId = $this->placeOrder();

        $this->expectException(InvalidTransitionException::class);

        Services::shipments()->ship($orderId, 'JNE', 'JNE1234567890');
    }

    public function testCancellationReleasesReservedStock(): void
    {
        $productId = $this->productId('panduan-codeigniter-4');
        $before    = $this->stockOf($productId);
        $orderId   = $this->placeOrder('panduan-codeigniter-4', 3);
        $this->assertSame($before - 3, $this->stockOf($productId));

        Services::orders()->cancelByCustomer($orderId, $this->customerId());

        $this->assertSame(OrderStatus::Cancelled->value, $this->statusOf($orderId));
        $this->assertSame($before, $this->stockOf($productId));
    }

    public function testCancelledOrderCannotBeReopenedOrCancelledTwice(): void
    {
        $orderId = $this->placeOrder();
        Services::orders()->cancelByCustomer($orderId, $this->customerId());

        $this->expectException(InvalidTransitionException::class);

        Services::orders()->transition($orderId, OrderStatus::Cancelled);
    }

    public function testCustomerCannotCancelPaidOrderOrSomeoneElsesOrder(): void
    {
        $orderId = $this->placeOrder();
        $this->submitProofDirectly($orderId);

        try {
            Services::orders()->cancelByCustomer($orderId, $this->customerId());
            $this->fail('Paid orders cannot be cancelled by customers');
        } catch (InvalidTransitionException) {
            $this->assertSame(OrderStatus::WaitingVerification->value, $this->statusOf($orderId));
        }

        $this->expectException(ShopException::class);
        Services::orders()->cancelByCustomer($orderId, $this->staffId('operator@shopease.test'));
    }

    public function testProofUploadIsRejectedForAnotherUsersOrder(): void
    {
        $orderId = $this->placeOrder();
        $file    = new UploadedFile('/dev/null', 'proof.png', 'image/png', 0, UPLOAD_ERR_OK);

        $payments = new PaymentService(
            $this->db,
            Services::orders(),
            new UploadService(config(Shop::class)),
            model(\App\Models\OrderModel::class),
            model(\App\Models\PaymentModel::class),
        );

        $this->expectException(ShopException::class);

        $payments->submitProof($this->staffId('operator@shopease.test'), $orderId, $file);
    }
}
