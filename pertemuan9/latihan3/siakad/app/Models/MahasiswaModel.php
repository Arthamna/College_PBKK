<?php

namespace App\Models;

use CodeIgniter\Model;

/** Aturan data mahasiswa dan akses tabel mahasiswa. */
class MahasiswaModel extends Model
{
	protected $table = 'mahasiswa';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['nim', 'nama', 'program_studi', 'angkatan', 'email', 'alamat'];
	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'id' => 'permit_empty|is_natural_no_zero',
		'nim' => 'required|max_length[30]|is_unique[mahasiswa.nim,id,{id}]',
		'nama' => 'required|max_length[100]',
		'program_studi' => 'required|max_length[100]',
		'angkatan' => 'required|integer|exact_length[4]|greater_than_equal_to[1900]|less_than_equal_to[2100]',
		'email' => 'permit_empty|valid_email|max_length[254]',
		'alamat' => 'permit_empty|max_length[2000]',
	];

	/** Mengelompokkan kondisi OR agar tetap aman saat query dikembangkan. */
	public function cari(string $kata_kunci): self
	{
		if ($kata_kunci !== '')
		{
			$this->groupStart()
				->like('nim', $kata_kunci)
				->orLike('nama', $kata_kunci)
				->orLike('program_studi', $kata_kunci)
				->groupEnd();
		}

		return $this;
	}
}
