<?php
namespace App\Models;
class CategoryModel extends \CodeIgniter\Model {
 protected $table='categories';
 protected $allowedFields=['name', 'slug', 'description', 'is_active'];
 protected $useTimestamps=true;
}
