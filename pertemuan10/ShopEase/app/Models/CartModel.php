<?php
namespace App\Models;
class CartModel extends \CodeIgniter\Model {
 protected $table='carts';
 protected $allowedFields=['user_id'];
 protected $useTimestamps=true;
}
