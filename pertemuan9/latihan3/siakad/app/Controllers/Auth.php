<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

/** Mengelola login dan logout administrator. */
class Auth extends BaseController
{
	public function index(): RedirectResponse
	{
		return redirect()->to(site_url(session()->has('user_id') ? 'admin' : 'login'));
	}

	public function login()
	{
		if (session()->has('user_id'))
		{
			return redirect()->to(site_url('admin'));
		}

		return view('auth/login', ['title' => lang('Siakad.login')]);
	}

	public function authenticate(): RedirectResponse
	{
		if (session()->has('user_id'))
		{
			return redirect()->to(site_url('admin'));
		}

		$input = $this->request->getPost(['username', 'password']);
		$rules = [
			'username' => 'required|alpha_dash|max_length[50]',
			'password' => 'required|max_length[4096]',
		];

		if ( ! $this->validateData($input, $rules))
		{
			return redirect()->to(site_url('login'))->with('error', lang('Siakad.login_invalid'));
		}

		$user_model = new UserModel();
		$user = $user_model->where('username', $input['username'])->first();

		if ($user === NULL OR ! password_verify($input['password'], $user['password_hash']))
		{
			return redirect()->to(site_url('login'))->with('error', lang('Siakad.login_invalid'));
		}

		session()->regenerate(TRUE);
		session()->set([
			'user_id' => (int) $user['id'],
			'nama' => $user['nama'],
			'username' => $user['username'],
		]);

		return redirect()->to(site_url('admin'))->with('success', lang('Siakad.login_success'));
	}

	public function logout(): RedirectResponse
	{
		session()->remove(['user_id', 'nama', 'username']);
		session()->destroy();

		return redirect()->to(site_url('login'));
	}
}
