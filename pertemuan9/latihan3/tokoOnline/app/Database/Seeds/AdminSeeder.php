<?php

namespace App\Database\Seeds;

use App\Models\AdminModel;
use CodeIgniter\Database\Seeder;
use RuntimeException;

/** Creates an initial administrator without overwriting an existing account. */
class AdminSeeder extends Seeder
{
	public function run()
	{
		$email = strtolower(trim((string) env('shop.adminEmail', '')));
		$password = (string) env('shop.adminPassword', '');
		$name = trim((string) env('shop.adminName', 'Admin Toko'));
		$admins = new AdminModel();

		if ($admins->where('email', $email)->first() !== NULL)
		{
			return;
		}

		if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) OR strlen($password) < 12)
		{
			throw new RuntimeException('Isi shop.adminEmail dan shop.adminPassword (minimal 12 karakter) pada .env.');
		}

		if ($admins->insert([
			'name' => $name,
			'email' => $email,
			'password_hash' => password_hash($password, PASSWORD_DEFAULT),
		]) === FALSE)
		{
			throw new RuntimeException(implode(' ', $admins->errors()));
		}
	}
}
