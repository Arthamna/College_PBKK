<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductImageController extends BaseController
{
	public function show(int $product_id)
	{
		$product = (new ProductModel())->find($product_id);

		if ($product === NULL)
		{
			throw PageNotFoundException::forPageNotFound();
		}

		$path = WRITEPATH . 'uploads/products/' . basename($product['image'] ?? '');

		if (empty($product['image']) OR ! is_file($path))
		{
			$path = FCPATH . 'assets/images/product-placeholder.svg';
		}

		$mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);

		return $this->response
			->setHeader('Content-Type', $mime)
			->setHeader('X-Content-Type-Options', 'nosniff')
			->setHeader('Cache-Control', 'no-cache')
			->setBody(file_get_contents($path));
	}
}
