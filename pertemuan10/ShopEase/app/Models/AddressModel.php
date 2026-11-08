<?php
namespace App\Models;
class AddressModel extends \CodeIgniter\Model {
 protected $table='addresses';
 protected $allowedFields=['user_id', 'recipient_name', 'phone', 'address', 'city', 'province', 'postal_code'];
 protected $useTimestamps=true;
}
