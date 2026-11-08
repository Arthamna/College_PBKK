<?php
namespace App\Models;
class CartItemModel extends \CodeIgniter\Model {
 protected $table='cart_items';
 protected $allowedFields=['cart_id', 'product_id', 'quantity'];
 protected $useTimestamps=true;
}
