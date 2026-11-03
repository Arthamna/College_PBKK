<?php

namespace App\Database\Seeds;

use App\Models\MahasiswaModel;
use CodeIgniter\Database\Seeder;
use RuntimeException;

/** Data fiktif untuk demonstrasi; aman dijalankan kembali. */
class MahasiswaSeeder extends Seeder
{
	public function run()
	{
		$mahasiswa_model = new MahasiswaModel($this->db);
		$daftar = [
			['2026001001', 'Alya Pratama', 'Informatika', 2026, 'alya@example.test', 'Surabaya'],
			['2026001002', 'Bima Saputra', 'Informatika', 2026, 'bima@example.test', 'Sidoarjo'],
			['2025002001', 'Citra Lestari', 'Sistem Informasi', 2025, 'citra@example.test', 'Malang'],
			['2025002002', 'Damar Wijaya', 'Sistem Informasi', 2025, 'damar@example.test', 'Surabaya'],
			['2024003001', 'Eka Permata', 'Teknik Komputer', 2024, 'eka@example.test', 'Gresik'],
		];

		foreach ($daftar as $data)
		{
			if ($mahasiswa_model->where('nim', $data[0])->first() !== NULL)
			{
				continue;
			}

			$result = $mahasiswa_model->insert(array_combine(
				['nim', 'nama', 'program_studi', 'angkatan', 'email', 'alamat'], $data
			));

			if ($result === FALSE)
			{
				throw new RuntimeException(implode(' ', $mahasiswa_model->errors()));
			}
		}
	}
}
