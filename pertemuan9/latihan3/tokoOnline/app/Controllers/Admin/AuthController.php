<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
	public function login()
	{
		if (session()->has('admin_id'))
		{
			return redirect()->to('/admin/products');
		}

		return view('admin/auth/login', ['title' => lang('Shop.login')]);
	}

	public function authenticate()
	{
		$data = $this->request->getPost(['email', 'password']);

		if ( ! $this->validateData($data, [
			'email' => 'required|valid_email|max_length[150]',
			'password' => 'required|string|max_length[1024]',
		]))
		{
			return redirect()->to('/admin/login')->with('error', lang('Shop.login_fields'));
		}

		$email = strtolower(trim($data['email']));
		$admin = (new AdminModel())->where('email', $email)->first();

		if ($admin === NULL OR ! password_verify($data['password'], $admin['password_hash']))
		{
			// Keep only the email; never flash a submitted password into session.
			return redirect()->to('/admin/login')
				->with('login_email', $email)
				->with('error', lang('Shop.login_invalid'));
		}

		session()->regenerate(TRUE);
		session()->set(['admin_id' => (int) $admin['id'], 'admin_name' => $admin['name']]);

		return redirect()->to('/admin/products')->with('success', lang('Shop.login_success'));
	}

	public function logout()
	{
		// Preserve the guest cart when the administrator leaves the admin area.
		session()->remove(['admin_id', 'admin_name']);
		session()->regenerate(TRUE);

		return redirect()->to('/admin/login')->with('success', lang('Shop.logout_success'));
	}
}
