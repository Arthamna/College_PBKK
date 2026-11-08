<?php
namespace App\Models;
class UserModel extends \CodeIgniter\Model {
 protected $table='users';
 protected $allowedFields=['name', 'email', 'password_hash', 'role', 'is_active'];
 protected $useTimestamps=true;
}
