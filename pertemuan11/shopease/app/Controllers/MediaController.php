<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\ProductStatus;
use App\Enums\Role;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\ProductModel;
use App\Services\UploadService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Streams files stored in writable/uploads. Uploaded files are never reachable
 * (or executable) through the web server directly.
 */
class MediaController extends BaseController
{
    public function product(int $id): ResponseInterface
    {
        $product = model(ProductModel::class)->find($id);

        if ($product === null || $product['image'] === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $staff = current_user()['role'] ?? null;

        if ($product['status'] !== ProductStatus::Active->value && ($staff === null || $staff === Role::Customer)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->stream(UploadService::PRODUCTS, (string) $product['image'], 'public, max-age=86400');
    }

    public function proof(int $orderId): ResponseInterface
    {
        $user    = $this->user();
        $payment = model(PaymentModel::class)->findByOrder($orderId);

        if ($payment === null || $payment['proof_file'] === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($user['role'] === Role::Customer && model(OrderModel::class)->findForUser($orderId, $user['id']) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->stream(UploadService::PROOFS, (string) $payment['proof_file'], 'private, no-store');
    }

    private function stream(string $directory, string $name, string $cache): ResponseInterface
    {
        $uploads = Services::uploads();
        $path    = $uploads->path($directory, $name) ?? throw PageNotFoundException::forPageNotFound();

        return $this->response
            ->setHeader('Content-Type', $uploads->mimeType($name))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Content-Security-Policy', "default-src 'none'; sandbox")
            ->setHeader('Cache-Control', $cache)
            ->setBody((string) file_get_contents($path));
    }
}
