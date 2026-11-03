<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use RuntimeException;

/** Membuat admin dari konfigurasi lokal tanpa menimpa akun yang ada. */
class AdminSeeder extends Seeder
{
	public function run()
	{
		$user_model = new UserModel($this->db);
		$username = (string) env('admin.username', 'admin');

		if ($user_model->where('username', $username)->first() !== NULL)
		{
			return;
		}

		$password = (string) env('admin.password', '');

		if (strlen($password) < 12)
		{
			throw new RuntimeException('Isi admin.password pada .env dengan minimal 12 karakter.');
		}

		$result = $user_model->insert([
			'nama' => (string) env('admin.nama', 'Administrator'),
			'username' => $username,
			'password_hash' => password_hash($password, PASSWORD_DEFAULT),
		]);

		if ($result === FALSE)
		{
			throw new RuntimeException(implode(' ', $user_model->errors()));
		}
	}
}
