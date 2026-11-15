<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Shop;

class CatalogController extends BaseController
{
    public function index(): string
    {
        $q          = trim((string) $this->request->getGet('q'));
        $categoryId = $this->request->getGet('category');
        $sort       = (string) $this->request->getGet('sort');

        $products = model(ProductModel::class)->catalog(
            $q !== '' ? mb_substr($q, 0, 100) : null,
            is_numeric($categoryId) ? (int) $categoryId : null,
            $sort,
        );

        return view('shop/catalog', [
            'products'   => $products->paginate(config(Shop::class)->perPage),
            'pager'      => $products->pager,
            'categories' => model(CategoryModel::class)->orderBy('name')->findAll(),
            'q'          => $q,
            'categoryId' => is_numeric($categoryId) ? (int) $categoryId : null,
            'sort'       => array_key_exists($sort, ProductModel::SORTS) ? $sort : 'newest',
        ]);
    }

    public function show(string $slug): string
    {
        $product = model(ProductModel::class)->findActiveBySlug($slug)
            ?? throw PageNotFoundException::forPageNotFound();

        return view('shop/product', ['product' => $product]);
    }
}
