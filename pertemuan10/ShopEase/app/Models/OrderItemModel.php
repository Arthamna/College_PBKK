<?php
namespace App\Models;
class OrderItemModel extends \CodeIgniter\Model {
 protected $table='order_items';
 protected $allowedFields=['order_id', 'product_id', 'product_name', 'price', 'quantity', 'subtotal'];
 protected $useTimestamps=true;
}
