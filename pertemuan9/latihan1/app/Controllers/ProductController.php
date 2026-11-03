<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Throwable;

class ProductController extends BaseController
{
    public function index(): string
    {
        $model = new ProductModel();
        $search = trim((string) $this->request->getGet('q'));
        $categoryId = (string) $this->request->getGet('category_id');
        $model->withCategory();

        if ($search !== '') {
            $model->like('products.name', $search);
        }

        if (ctype_digit($categoryId) && (int) $categoryId > 0) {
            $model->where('products.category_id', (int) $categoryId);
        }

        return view('products/index', [
            'title'      => 'Barang',
            'products'   => $model->orderBy('products.id', 'DESC')->paginate(10),
            'pager'      => $model->pager,
            'categories' => (new CategoryModel())->orderBy('name')->findAll(),
            'search'     => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function create(): string
    {
        return $this->form('products/create', 'Tambah Barang');
    }

    public function store()
    {
        return $this->saveProduct();
    }

    public function edit(int $id): string
    {
        return $this->form('products/edit', 'Edit Barang', $this->findProduct($id));
    }

    public function update(int $id)
    {
        return $this->saveProduct($id);
    }

    public function delete(int $id)
    {
        $product = $this->findProduct($id);

        if ((new ProductModel())->delete($id)) {
            $this->removeImage($product['image']);
        }

        return redirect()->to(site_url('products'))->with('success', 'Barang berhasil dihapus.');
    }

    public function image(int $id)
    {
        $product = $this->findProduct($id);
        $name = $product['image'];
        $path = WRITEPATH . 'uploads/products/' . basename((string) $name);

        if (! $name || ! is_file($path)) {
            throw PageNotFoundException::forPageNotFound('Foto tidak ditemukan.');
        }

        return $this->response
            ->setContentType(mime_content_type($path))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody(file_get_contents($path));
    }

    private function form(string $view, string $title, array $product = []): string
    {
        return view($view, [
            'title'      => $title,
            'product'    => $product,
            'categories' => (new CategoryModel())->orderBy('name')->findAll(),
        ]);
    }

    private function saveProduct(?int $id = null)
    {
        $product = $id === null ? null : $this->findProduct($id);
        $data = [
            'category_id' => (string) $this->request->getPost('category_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'stock'       => (string) $this->request->getPost('stock'),
            'price'       => (string) $this->request->getPost('price'),
        ];
        $model = new ProductModel();
        $rules = $model->getValidationRules();
        $image = $this->request->getFile('image');
        $hasUpload = $image !== null && $image->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasUpload) {
            $rules['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|ext_in[image,jpg,jpeg,png,webp]|max_size[image,2048]';
        }

        if (! $this->validateData($data, $rules, $model->getValidationMessages())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $newImage = null;

        try {
            if ($hasUpload) {
                $directory = WRITEPATH . 'uploads/products';

                if (! is_dir($directory)) {
                    mkdir($directory, 0775, true);
                }

                $newImage = $image->getRandomName();
                $image->move($directory, $newImage);
                $data['image'] = $newImage;
            }

            $saved = $id === null ? $model->insert($data) : $model->update($id, $data);

            if (! $saved) {
                $this->removeImage($newImage);

                return redirect()->back()->withInput()->with('errors', $model->errors());
            }
        } catch (Throwable $exception) {
            $this->removeImage($newImage);
            log_message('error', 'Gagal menyimpan barang: {message}', ['message' => $exception->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'Barang gagal disimpan. Periksa kategori dan izin folder upload, lalu coba lagi.');
        }

        if ($newImage !== null && $product !== null) {
            $this->removeImage($product['image']);
        }

        return redirect()->to(site_url('products'))->with('success', $id === null ? 'Barang berhasil ditambahkan.' : 'Barang berhasil diperbarui.');
    }

    private function findProduct(int $id): array
    {
        return (new ProductModel())->find($id) ?? throw PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
    }

    private function removeImage(?string $name): void
    {
        if ($name !== null) {
            $path = WRITEPATH . 'uploads/products/' . basename($name);

            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
