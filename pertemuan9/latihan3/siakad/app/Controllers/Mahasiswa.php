<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

/** Menerima request CRUD; aturan penyimpanan berada pada model. */
class Mahasiswa extends BaseController
{
	public function index(): string
	{
		$mahasiswa_model = new MahasiswaModel();
		$kata_kunci = $this->request->getGet('q');
		$kata_kunci = is_string($kata_kunci) ? trim($kata_kunci) : '';

		return view('mahasiswa/index', [
			'title' => lang('Siakad.mahasiswa'),
			'active_menu' => 'mahasiswa',
			'kata_kunci' => $kata_kunci,
			'mahasiswa' => $mahasiswa_model->cari($kata_kunci)->orderBy('nim', 'ASC')->paginate(10, 'mahasiswa'),
			'pager' => $mahasiswa_model->pager,
		]);
	}

	public function create(): string
	{
		return view('mahasiswa/create', [
			'title' => lang('Siakad.add_mahasiswa'),
			'active_menu' => 'mahasiswa',
			'mahasiswa' => [],
		]);
	}

	public function store(): RedirectResponse
	{
		$mahasiswa_model = new MahasiswaModel();

		if ($mahasiswa_model->insert($this->_input()) === FALSE)
		{
			return redirect()->to(site_url('admin/mahasiswa/create'))
				->withInput()->with('errors', $mahasiswa_model->errors());
		}

		return redirect()->to(site_url('admin/mahasiswa'))->with('success', lang('Siakad.created'));
	}

	public function edit(string $id): string
	{
		return view('mahasiswa/edit', [
			'title' => lang('Siakad.edit_mahasiswa'),
			'active_menu' => 'mahasiswa',
			'mahasiswa' => $this->_find($id),
		]);
	}

	public function update(string $id): RedirectResponse
	{
		$this->_find($id);
		$mahasiswa_model = new MahasiswaModel();

		// Placeholder validasi unik memakai ID tepercaya dari route.
		if ($mahasiswa_model->update($id, $this->_input() + ['id' => $id]) === FALSE)
		{
			return redirect()->to(site_url('admin/mahasiswa/' . $id . '/edit'))
				->withInput()->with('errors', $mahasiswa_model->errors());
		}

		return redirect()->to(site_url('admin/mahasiswa'))->with('success', lang('Siakad.updated'));
	}

	public function delete(string $id): RedirectResponse
	{
		$this->_find($id);
		$mahasiswa_model = new MahasiswaModel();
		$deleted = $mahasiswa_model->delete($id);

		return redirect()->to(site_url('admin/mahasiswa'))
			->with($deleted ? 'success' : 'error', lang($deleted ? 'Siakad.deleted' : 'Siakad.save_failed'));
	}

	/** Mengambil hanya kolom form; ID selalu berasal dari route. */
	private function _input(): array
	{
		$input = $this->request->getPost(['nim', 'nama', 'program_studi', 'angkatan', 'email', 'alamat']);

		foreach ($input as $field => $value)
		{
			if (is_string($value))
			{
				$input[$field] = trim($value);
			}
		}

		return $input;
	}

	private function _find(string $id): array
	{
		$mahasiswa_model = new MahasiswaModel();
		$mahasiswa = $mahasiswa_model->find($id);

		if ($mahasiswa === NULL)
		{
			throw PageNotFoundException::forPageNotFound(lang('Siakad.not_found'));
		}

		return $mahasiswa;
	}
}
