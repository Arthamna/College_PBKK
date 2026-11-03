<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryController extends BaseController
{
    public function index(): string
    {
        return view('categories/index', [
            'title'      => 'Kategori',
            'categories' => (new CategoryModel())->orderBy('name')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('categories/create', ['title' => 'Tambah Kategori', 'category' => []]);
    }

    public function store()
    {
        $model = new CategoryModel();

        if (! $model->insert(['name' => trim((string) $this->request->getPost('name'))])) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to(site_url('categories'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        return view('categories/edit', ['title' => 'Edit Kategori', 'category' => $this->findCategory($id)]);
    }

    public function update(int $id)
    {
        $this->findCategory($id);
        $model = new CategoryModel();

        if (! $model->update($id, ['name' => trim((string) $this->request->getPost('name'))])) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to(site_url('categories'))->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->findCategory($id);

        if ((new ProductModel())->where('category_id', $id)->countAllResults() > 0) {
            return redirect()->to(site_url('categories'))->with('error', 'Kategori masih digunakan oleh barang dan tidak dapat dihapus.');
        }

        (new CategoryModel())->delete($id);

        return redirect()->to(site_url('categories'))->with('success', 'Kategori berhasil dihapus.');
    }

    private function findCategory(int $id): array
    {
        return (new CategoryModel())->find($id) ?? throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
    }
}
