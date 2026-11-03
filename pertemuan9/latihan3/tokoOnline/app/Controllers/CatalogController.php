<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CatalogController extends BaseController
{
	public function index()
	{
		$products = new ProductModel();

		return view('catalog/index', [
			'title' => lang('Shop.catalog'),
			'products' => $products->orderBy('id', 'DESC')->paginate(12),
			'pager' => $products->pager,
			'product_count' => $products->pager->getTotal(),
		]);
	}

	public function show(int $product_id)
	{
		$product = (new ProductModel())->find($product_id);

		if ($product === NULL)
		{
			throw PageNotFoundException::forPageNotFound(lang('Shop.product_missing'));
		}

		return view('catalog/show', ['title' => $product['name'], 'product' => $product]);
	}
}
