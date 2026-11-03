<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        return redirect()->to(site_url(session()->has('user_id') ? 'dashboard' : 'login'));
    }

    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function authenticate()
    {
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
        ];

        if (! $this->validateData($data, ['username' => 'required|max_length[50]', 'password' => 'required|max_length[255]'])) {
            return redirect()->to(site_url('login'))->with('errors', $this->validator->getErrors());
        }

        $user = (new UserModel())->where('username', $data['username'])->first();

        if ($user === null || ! password_verify($data['password'], $user['password'])) {
            return redirect()->to(site_url('login'))->with('error', 'Username atau password salah.');
        }

        session()->regenerate(true);
        session()->set(['user_id' => (int) $user['id'], 'username' => $user['username'], 'role' => $user['role']]);

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
