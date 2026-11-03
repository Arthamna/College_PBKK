<?php

namespace App\Controllers;

use App\Libraries\Cart;
use DomainException;

class CartController extends BaseController
{
	public function index()
	{
		return view('cart/index', [
			'title' => lang('Shop.cart'),
			'cart' => (new Cart())->summary(),
		]);
	}

	public function add()
	{
		$data = $this->request->getPost(['product_id', 'quantity']);

		if ( ! $this->validateData($data, [
			'product_id' => 'required|is_natural_no_zero|less_than_equal_to[2147483647]',
			'quantity' => 'required|is_natural_no_zero|less_than_equal_to[1000000]',
		]))
		{
			return redirect()->to('/cart')->with('error', lang('Shop.quantity_invalid'));
		}

		try
		{
			(new Cart())->add((int) $data['product_id'], (int) $data['quantity']);
		}
		catch (DomainException $exception)
		{
			return redirect()->to('/cart')->with('error', $exception->getMessage());
		}

		return redirect()->to('/cart')->with('success', lang('Shop.cart_added'));
	}

	public function update(int $product_id)
	{
		$data = $this->request->getPost(['quantity']);

		if ( ! $this->validateData($data, [
			'quantity' => 'required|is_natural_no_zero|less_than_equal_to[1000000]',
		]))
		{
			return redirect()->to('/cart')->with('error', lang('Shop.quantity_invalid'));
		}

		try
		{
			(new Cart())->update($product_id, (int) $data['quantity']);
		}
		catch (DomainException $exception)
		{
			return redirect()->to('/cart')->with('error', $exception->getMessage());
		}

		return redirect()->to('/cart')->with('success', lang('Shop.cart_updated'));
	}

	public function remove(int $product_id)
	{
		(new Cart())->remove($product_id);

		return redirect()->to('/cart')->with('success', lang('Shop.cart_removed'));
	}

	public function clear()
	{
		(new Cart())->clear();

		return redirect()->to('/cart')->with('success', lang('Shop.cart_cleared'));
	}
}
