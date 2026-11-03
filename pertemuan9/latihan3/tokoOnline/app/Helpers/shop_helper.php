<?php

/** Formats whole rupiah without relying on floating point amounts. */
function rupiah(int $amount): string
{
	return 'Rp ' . number_format($amount, 0, ',', '.');
}

function product_image(array $product): string
{
	return empty($product['image'])
		? base_url('assets/images/product-placeholder.svg')
		: site_url('products/' . $product['id'] . '/image');
}

function cart_count(): int
{
	return array_sum(session()->get('cart') ?? []);
}

/** Invalid array-shaped input must not become an HTML attribute or a notice. */
function input_value(string $field, $default = ''): string
{
	$value = old($field, $default, FALSE);

	return esc(is_scalar($value) ? (string) $value : '', 'attr');
}
