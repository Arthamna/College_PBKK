<?php

namespace App\Libraries;

use App\Models\ProductModel;
use DomainException;

/** Session contains only quantities; database remains the source of prices. */
class Cart
{
	public function add(int $product_id, int $quantity): void
	{
		$product = $this->_find_product($product_id);
		$cart = session()->get('cart') ?? [];
		$total_quantity = ($cart[$product_id] ?? 0) + $quantity;
		$this->_check_quantity($quantity, $total_quantity, (int) $product['stock']);
		$cart[$product_id] = $total_quantity;
		session()->set('cart', $cart);
	}

	public function update(int $product_id, int $quantity): void
	{
		$cart = session()->get('cart') ?? [];

		if ( ! isset($cart[$product_id]))
		{
			throw new DomainException(lang('Shop.cart_missing'));
		}

		$product = $this->_find_product($product_id);
		$this->_check_quantity($quantity, $quantity, (int) $product['stock']);
		$cart[$product_id] = $quantity;
		session()->set('cart', $cart);
	}

	public function remove(int $product_id): void
	{
		$cart = session()->get('cart') ?? [];
		unset($cart[$product_id]);
		session()->set('cart', $cart);
	}

	public function clear(): void
	{
		session()->remove('cart');
	}

	/** Refreshes prices and reconciles deleted products or reduced stock. */
	public function summary(): array
	{
		$cart = session()->get('cart') ?? [];
		$items = [];
		$normalized = [];
		$total = 0;
		$count = 0;
		$products = $cart === [] ? [] : (new ProductModel())->whereIn('id', array_keys($cart))->findAll();

		foreach ($products as $product)
		{
			$product_id = (int) $product['id'];
			$quantity = min((int) $cart[$product_id], (int) $product['stock']);

			if ($quantity < 1)
			{
				continue;
			}

			$subtotal = (int) $product['price'] * $quantity;
			$items[] = ['product' => $product, 'quantity' => $quantity, 'subtotal' => $subtotal];
			$normalized[$product_id] = $quantity;
			$total += $subtotal;
			$count += $quantity;
		}

		// Compare values rather than array order, which can differ from SQL order.
		$adjusted = $cart != $normalized;
		session()->set('cart', $normalized);

		return compact('items', 'total', 'count', 'adjusted');
	}

	private function _find_product(int $product_id): array
	{
		$product = (new ProductModel())->find($product_id);

		if ($product === NULL)
		{
			throw new DomainException(lang('Shop.product_missing'));
		}

		return $product;
	}

	private function _check_quantity(int $quantity, int $total_quantity, int $stock): void
	{
		if ($quantity < 1)
		{
			throw new DomainException(lang('Shop.quantity_invalid'));
		}

		if ($total_quantity > $stock)
		{
			throw new DomainException(lang('Shop.stock_insufficient', [$stock]));
		}
	}
}
