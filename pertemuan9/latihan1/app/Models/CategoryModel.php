<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $allowedFields = ['name'];
    protected $returnType = 'array';
    protected $validationRules = [
        'name' => 'required|max_length[100]|is_unique[categories.name,id,{id}]',
        'id'   => 'permit_empty|is_natural_no_zero',
    ];
}
