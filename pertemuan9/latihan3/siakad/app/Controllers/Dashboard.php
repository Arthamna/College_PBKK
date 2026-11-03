<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

/** Ringkasan data akademik untuk administrator. */
class Dashboard extends BaseController
{
	public function index(): string
	{
		$mahasiswa_model = new MahasiswaModel();

		return view('dashboard/index', [
			'title' => lang('Siakad.dashboard'),
			'active_menu' => 'dashboard',
			'total_mahasiswa' => $mahasiswa_model->countAllResults(),
			'mahasiswa_terbaru' => $mahasiswa_model->orderBy('id', 'DESC')->findAll(5),
		]);
	}
}
