<?php
namespace App\Models;
class OrderModel extends \CodeIgniter\Model {
 protected $table='orders';
 protected $allowedFields=['user_id', 'address_id', 'order_number', 'checkout_token', 'address_snapshot', 'subtotal', 'shipping_cost', 'total', 'order_status', 'notes', 'changed_by'];
 protected $useTimestamps=true;
}
