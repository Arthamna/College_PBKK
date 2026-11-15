<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Enums\ProductStatus;
use App\Exceptions\ShopException;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Services\UploadService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class ProductController extends BaseController
{
    public function index(): string
    {
        $products = model(ProductModel::class)->catalog(null, null, 'name', false);

        return view('admin/products/index', [
            'products' => $products->paginate(15),
            'pager'    => $products->pager,
            'isAdmin'  => $this->user()['role']->value === 'administrator',
        ]);
    }

    public function new(): string
    {
        return view('admin/products/form', [
            'product'    => null,
            'categories' => model(CategoryModel::class)->orderBy('name')->findAll(),
        ]);
    }

    public function create(): RedirectResponse
    {
        $products = model(ProductModel::class);
        $data     = $this->payload($products, null);

        try {
            $image = $this->storeImage();
        } catch (ShopException $e) {
            return $this->failWith('admin/products/new', $e, true);
        }

        if ($image !== null) {
            $data['image'] = $image;
        }

        if (! $products->insert($data)) {
            Services::uploads()->delete(UploadService::PRODUCTS, $image);

            return redirect()->back()->withInput()->with('errors', $products->errors());
        }

        return $this->back('admin/products', 'Produk ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('admin/products/form', [
            'product'    => $this->find($id),
            'categories' => model(CategoryModel::class)->orderBy('name')->findAll(),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $products = model(ProductModel::class);
        $current  = $this->find($id);
        $data     = $this->payload($products, $id);

        try {
            $image = $this->storeImage();
        } catch (ShopException $e) {
            return $this->failWith('admin/products/' . $id . '/edit', $e, true);
        }

        if ($image !== null) {
            $data['image'] = $image;
        }

        if (! $products->update($id, $data)) {
            Services::uploads()->delete(UploadService::PRODUCTS, $image);

            return redirect()->back()->withInput()->with('errors', $products->errors());
        }

        if ($image !== null) {
            Services::uploads()->delete(UploadService::PRODUCTS, $current['image']);
        }

        return $this->back('admin/products', 'Produk diperbarui.');
    }

    /**
     * Products that were ever ordered are deactivated to keep order history intact;
     * products without history are removed.
     */
    public function delete(int $id): RedirectResponse
    {
        $products = model(ProductModel::class);
        $product  = $this->find($id);

        if ($products->hasOrders($id)) {
            $products->update($id, ['status' => ProductStatus::Inactive->value]);

            return $this->back('admin/products', 'Produk memiliki riwayat pesanan, sehingga dinonaktifkan.');
        }

        $products->delete($id);
        Services::uploads()->delete(UploadService::PRODUCTS, $product['image']);

        return $this->back('admin/products', 'Produk dihapus.');
    }

    public function updateStock(int $id): RedirectResponse
    {
        $this->find($id);

        if (! $this->validate(['stock' => 'required|is_natural|less_than[1000000]'])) {
            return $this->back('admin/products', null, 'Stok harus berupa angka nol atau lebih.');
        }

        model(ProductModel::class)->update($id, ['stock' => (int) $this->request->getPost('stock')]);

        return $this->back('admin/products', 'Stok diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ProductModel $products, ?int $exceptId): array
    {
        $name = trim((string) $this->request->getPost('name'));
        $base = url_title($name, '-', true) ?: 'produk';
        $slug = $base;

        for ($i = 2; $products->slugExists($slug, $exceptId); $i++) {
            $slug = $base . '-' . $i;
        }

        return [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')),
            'price'       => (string) $this->request->getPost('price'),
            'stock'       => (string) $this->request->getPost('stock'),
            'status'      => (string) $this->request->getPost('status'),
        ];
    }

    private function storeImage(): ?string
    {
        $file = $this->request->getFile('image');

        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        return Services::uploads()->store($file, UploadService::PRODUCTS);
    }

    /**
     * @return array<string, mixed>
     */
    private function find(int $id): array
    {
        return model(ProductModel::class)->find($id) ?? throw PageNotFoundException::forPageNotFound();
    }
}
