<?php

namespace App\Models;

use CodeIgniter\Model;

/** Akses akun administrator; password hanya disimpan sebagai hash. */
class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['nama', 'username', 'password_hash'];
	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'nama' => 'required|max_length[100]',
		'username' => 'required|alpha_dash|max_length[50]|is_unique[users.username]',
		'password_hash' => 'required|max_length[255]',
	];
}
