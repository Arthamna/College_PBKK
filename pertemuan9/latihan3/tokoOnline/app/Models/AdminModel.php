<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
	protected $table = 'admins';
	protected $returnType = 'array';
	protected $allowedFields = ['name', 'email', 'password_hash'];
	protected $useTimestamps = TRUE;
	protected $validationRules = [
		'name' => 'required|max_length[100]',
		'email' => 'required|valid_email|max_length[150]',
		'password_hash' => 'required|max_length[255]',
	];
}
