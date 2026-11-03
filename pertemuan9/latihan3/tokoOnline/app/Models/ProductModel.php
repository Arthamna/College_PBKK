<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
	protected $table = 'products';
	protected $returnType = 'array';
	protected $allowedFields = ['name', 'description', 'price', 'stock', 'image'];
	protected $useTimestamps = TRUE;
	protected $validationRules = [
		'name' => 'required|string|max_length[150]',
		'description' => 'permit_empty|string|max_length[5000]',
		'price' => 'required|is_natural_no_zero|less_than_equal_to[2147483647]',
		'stock' => 'required|is_natural|less_than_equal_to[1000000]',
	];
	protected $validationMessages = [
		'name' => [
			'required' => 'Shop.name_required',
			'string' => 'Shop.name_required',
			'max_length' => 'Shop.name_length',
		],
		'description' => [
			'string' => 'Shop.description_invalid',
			'max_length' => 'Shop.description_invalid',
		],
		'price' => [
			'required' => 'Shop.price_invalid',
			'is_natural_no_zero' => 'Shop.price_invalid',
			'less_than_equal_to' => 'Shop.price_invalid',
		],
		'stock' => [
			'required' => 'Shop.stock_invalid',
			'is_natural' => 'Shop.stock_invalid',
			'less_than_equal_to' => 'Shop.stock_invalid',
		],
	];
}
