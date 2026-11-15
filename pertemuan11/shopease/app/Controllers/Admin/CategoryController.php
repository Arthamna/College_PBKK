<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class CategoryController extends BaseController
{
    public function index(): string
    {
        return view('admin/categories/index', ['categories' => model(CategoryModel::class)->withProductCount()]);
    }

    public function new(): string
    {
        return view('admin/categories/form', ['category' => null]);
    }

    public function create(): RedirectResponse
    {
        $categories = model(CategoryModel::class);
        $name       = trim((string) $this->request->getPost('name'));

        $data = [
            'name'        => $name,
            'slug'        => $this->uniqueSlug($categories, $name, null),
            'description' => trim((string) $this->request->getPost('description')),
        ];

        if (! $categories->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $categories->errors());
        }

        return $this->back('admin/categories', 'Kategori ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('admin/categories/form', ['category' => $this->find($id)]);
    }

    public function update(int $id): RedirectResponse
    {
        $categories = model(CategoryModel::class);
        $this->find($id);
        $name = trim((string) $this->request->getPost('name'));

        $data = [
            'name'        => $name,
            'slug'        => $this->uniqueSlug($categories, $name, $id),
            'description' => trim((string) $this->request->getPost('description')),
        ];

        if (! $categories->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $categories->errors());
        }

        return $this->back('admin/categories', 'Kategori diperbarui.');
    }

    public function delete(int $id): RedirectResponse
    {
        $category = $this->find($id);
        $counts   = array_column(model(CategoryModel::class)->withProductCount(), 'product_count', 'id');

        if ((int) ($counts[$id] ?? 0) > 0) {
            return $this->back('admin/categories', null, 'Kategori masih memiliki produk dan tidak dapat dihapus.');
        }

        model(CategoryModel::class)->delete((int) $category['id']);

        return $this->back('admin/categories', 'Kategori dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function find(int $id): array
    {
        return model(CategoryModel::class)->find($id) ?? throw PageNotFoundException::forPageNotFound();
    }

    private function uniqueSlug(CategoryModel $categories, string $name, ?int $exceptId): string
    {
        $base = url_title($name, '-', true) ?: 'kategori';
        $slug = $base;

        for ($i = 2; $categories->slugExists($slug, $exceptId); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }
}
