<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Menyiapkan akun admin dan contoh mahasiswa. */
class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$this->call(AdminSeeder::class);
		$this->call(MahasiswaSeeder::class);
	}
}
