<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $allowedFields = ['category_id', 'name', 'stock', 'price', 'image'];
    protected $returnType = 'array';
    protected $validationRules = [
        'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
        'name'        => 'required|max_length[150]',
        'stock'       => 'required|is_natural|less_than_equal_to[2147483647]',
        'price'       => 'required|regex_match[/^\d{1,13}(\.\d{1,2})?$/]',
    ];
    protected $validationMessages = [
        'category_id' => ['is_not_unique' => 'Kategori yang dipilih tidak tersedia.'],
        'price'       => ['regex_match' => 'Harga harus positif atau nol, maksimal 13 digit dan 2 angka desimal.'],
    ];

    public function withCategory(): self
    {
        return $this->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id');
    }
}
