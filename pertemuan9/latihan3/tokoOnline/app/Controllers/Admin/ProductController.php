<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Throwable;

class ProductController extends BaseController
{
	public function index()
	{
		$products = new ProductModel();

		return view('admin/products/index', [
			'title' => lang('Shop.products'),
			'products' => $products->orderBy('id', 'DESC')->paginate(10),
			'pager' => $products->pager,
			'product_count' => $products->pager->getTotal(),
		]);
	}

	public function create()
	{
		return view('admin/products/create', ['title' => lang('Shop.add_product'), 'product' => []]);
	}

	public function store()
	{
		return $this->_save();
	}

	public function edit(int $product_id)
	{
		return view('admin/products/edit', [
			'title' => lang('Shop.edit_product'),
			'product' => $this->_find_product($product_id),
		]);
	}

	public function update(int $product_id)
	{
		return $this->_save($this->_find_product($product_id));
	}

	public function delete(int $product_id)
	{
		$product = $this->_find_product($product_id);

		if ((new ProductModel())->delete($product_id) === FALSE)
		{
			return redirect()->to('/admin/products')->with('error', lang('Shop.delete_failed'));
		}

		$this->_delete_image($product['image']);

		return redirect()->to('/admin/products')->with('success', lang('Shop.product_deleted'));
	}

	/** Validates before moving an upload and only removes old files after success. */
	private function _save(?array $product = NULL)
	{
		$products = new ProductModel();
		$data = $this->request->getPost(['name', 'description', 'price', 'stock']);

		foreach (['name', 'description'] as $field)
		{
			if (is_string($data[$field]))
			{
				$data[$field] = trim($data[$field]);
			}
		}

		$form_url = $product === NULL ? '/admin/products/create' : '/admin/products/' . $product['id'] . '/edit';
		$rules = $products->getValidationRules();
		$file = $this->request->getFile('image');

		if ($file !== NULL && $file->getError() !== UPLOAD_ERR_NO_FILE)
		{
			$rules['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpeg,image/png,image/webp]|ext_in[image,jpg,jpeg,png,webp]|max_size[image,2048]';
		}

		$messages = $products->getValidationMessages();
		$messages['image'] = array_fill_keys(
			['uploaded', 'is_image', 'mime_in', 'ext_in', 'max_size'],
			lang('Shop.image_invalid')
		);

		if ( ! $this->validateData($data, $rules, $messages))
		{
			return redirect()->to($form_url)->withInput()->with('errors', $this->validator->getErrors());
		}

		$new_image = NULL;

		try
		{
			if ($file !== NULL && $file->getError() !== UPLOAD_ERR_NO_FILE)
			{
				$new_image = $file->getRandomName();
				$file->move(WRITEPATH . 'uploads/products', $new_image);
				$data['image'] = $new_image;
			}
		}
		catch (HTTPException $exception)
		{
			log_message('error', 'Product image upload failed: {message}', ['message' => $exception->getMessage()]);

			return redirect()->to($form_url)->withInput()->with('error', lang('Shop.upload_failed'));
		}

		try
		{
			$saved = $product === NULL
				? $products->insert($data) !== FALSE
				: $products->update($product['id'], $data);
		}
		catch (Throwable $exception)
		{
			$this->_delete_image($new_image);
			log_message('error', 'Product save failed: {message}', ['message' => $exception->getMessage()]);

			return redirect()->to($form_url)->withInput()->with('error', lang('Shop.save_failed'));
		}

		if ( ! $saved)
		{
			$this->_delete_image($new_image);

			return redirect()->to($form_url)->withInput()->with('errors', $products->errors());
		}

		if ($product !== NULL && $new_image !== NULL)
		{
			$this->_delete_image($product['image']);
		}

		return redirect()->to('/admin/products')->with('success', lang(
			$product === NULL ? 'Shop.product_created' : 'Shop.product_updated'
		));
	}

	private function _find_product(int $product_id): array
	{
		$product = (new ProductModel())->find($product_id);

		if ($product === NULL)
		{
			throw PageNotFoundException::forPageNotFound(lang('Shop.product_missing'));
		}

		return $product;
	}

	private function _delete_image(?string $filename): void
	{
		if ($filename === NULL OR $filename === '')
		{
			return;
		}

		$path = WRITEPATH . 'uploads/products/' . basename($filename);

		if (is_file($path) && ! unlink($path))
		{
			log_message('error', 'Unable to remove product image: {filename}', ['filename' => basename($filename)]);
		}
	}
}
