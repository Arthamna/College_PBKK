<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['username', 'password', 'role'];
    protected $returnType = 'array';
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|alpha_numeric_punct|is_unique[users.username,id,{id}]',
        'password' => 'required|max_length[255]',
        'role'     => 'required|in_list[admin,staff]',
        'id'       => 'permit_empty|is_natural_no_zero',
    ];
}
