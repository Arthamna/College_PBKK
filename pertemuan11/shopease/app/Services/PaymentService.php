<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\InvalidTransitionException;
use App\Exceptions\ShopException;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\Files\UploadedFile;

class PaymentService
{
    public function __construct(
        private readonly BaseConnection $db,
        private readonly OrderService $orderService,
        private readonly UploadService $uploads,
        private readonly OrderModel $orders,
        private readonly PaymentModel $payments,
    ) {
    }

    public function submitProof(int $userId, int $orderId, UploadedFile $file): void
    {
        $order = $this->orders->findForUser($orderId, $userId)
            ?? throw new ShopException('Pesanan tidak ditemukan.');

        $payment = $this->payments->findByOrder($orderId)
            ?? throw new ShopException('Data pembayaran tidak ditemukan.');

        $status = PaymentStatus::from($payment['status']);

        if ($order['status'] !== OrderStatus::PendingPayment->value || ! $status->canUploadProof()) {
            throw InvalidTransitionException::because('Bukti pembayaran tidak dapat diunggah untuk pesanan ini.');
        }

        $newFile = $this->uploads->store($file, UploadService::PROOFS);

        $this->db->transBegin();

        try {
            $this->payments->update((int) $payment['id'], [
                'proof_file'       => $newFile,
                'status'           => PaymentStatus::WaitingVerification->value,
                'rejection_reason' => null,
            ]);
            $this->orderService->transition($orderId, OrderStatus::WaitingVerification);

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();
            $this->uploads->delete(UploadService::PROOFS, $newFile);

            throw $e;
        }

        $this->uploads->delete(UploadService::PROOFS, $payment['proof_file']);
    }

    public function verify(int $staffId, int $orderId): void
    {
        $payment = $this->waitingPayment($orderId);

        $this->db->transBegin();

        try {
            $this->payments->update((int) $payment['id'], [
                'status'           => PaymentStatus::Verified->value,
                'verified_by'      => $staffId,
                'verified_at'      => date('Y-m-d H:i:s'),
                'rejection_reason' => null,
            ]);
            $this->orderService->transition($orderId, OrderStatus::Processing);

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    public function reject(int $staffId, int $orderId, string $reason): void
    {
        $payment = $this->waitingPayment($orderId);

        $this->db->transBegin();

        try {
            $this->payments->update((int) $payment['id'], [
                'status'           => PaymentStatus::Rejected->value,
                'verified_by'      => $staffId,
                'verified_at'      => date('Y-m-d H:i:s'),
                'rejection_reason' => mb_substr(trim($reason), 0, 255),
            ]);
            $this->orderService->transition($orderId, OrderStatus::PendingPayment);

            $this->db->transCommit();
        } catch (ShopException | DatabaseException $e) {
            $this->db->transRollback();

            throw $e;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function waitingPayment(int $orderId): array
    {
        $payment = $this->payments->findByOrder($orderId)
            ?? throw new ShopException('Data pembayaran tidak ditemukan.');

        if ($payment['status'] !== PaymentStatus::WaitingVerification->value) {
            throw InvalidTransitionException::because('Pembayaran ini tidak sedang menunggu verifikasi.');
        }

        return $payment;
    }
}
