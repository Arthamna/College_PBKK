<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Role;
use App\Exceptions\ShopException;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

class AuthController extends BaseController
{
    private const int MAX_ATTEMPTS = 5;

    public function login(): string
    {
        return view('auth/login');
    }

    public function authenticate(): RedirectResponse
    {
        if (! $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $throttleKey = 'login_' . md5($this->request->getIPAddress());

        if (! Services::throttler()->check($throttleKey, self::MAX_ATTEMPTS, MINUTE)) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan. Coba lagi dalam satu menit.');
        }

        $user = Services::auth()->attempt(
            (string) $this->request->getPost('email'),
            (string) $this->request->getPost('password'),
        );

        if ($user === null) {
            // Generic message: never reveal whether the email exists.
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        $intended = (string) session()->get('intended');
        session()->regenerate(true); // prevent session fixation
        session()->set([
            'user_id' => (int) $user['id'],
            'name'    => (string) $user['name'],
            'role'    => (string) $user['role'],
        ]);
        session()->remove('intended');

        $isStaff = Role::from($user['role'])->isStaff();
        $target  = $isStaff ? 'admin' : 'products';

        if ($intended !== '' && str_starts_with($intended, '/') && ! str_starts_with($intended, '//')) {
            $target = ltrim($intended, '/');
        }

        return $this->back($target, 'Selamat datang, ' . $user['name'] . '.');
    }

    public function register(): string
    {
        return view('auth/register');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[100]',
            'email'    => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'phone'    => 'permit_empty|max_length[30]|regex_match[/^[0-9+\-\s]+$/]',
            'password' => 'required|min_length[8]|max_length[72]',
            'confirm'  => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        try {
            Services::auth()->register(
                (string) $this->request->getPost('name'),
                (string) $this->request->getPost('email'),
                (string) $this->request->getPost('password'),
                $this->request->getPost('phone') !== null ? (string) $this->request->getPost('phone') : null,
            );
        } catch (ShopException $e) {
            return $this->failWith('register', $e, true);
        }

        return $this->back('login', 'Registrasi berhasil. Silakan masuk.');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return $this->back('/', 'Anda telah keluar.');
    }
}
