<?php
namespace App\Models;
class ProductModel extends \CodeIgniter\Model {
 protected $table='products';
 protected $allowedFields=['category_id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'status'];
 protected $useTimestamps=true;
}
