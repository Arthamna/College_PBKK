<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UserController extends BaseController
{
    public function index(): string
    {
        return view('users/index', [
            'title' => 'Pengguna',
            'users' => (new UserModel())->select('id, username, role')->orderBy('username')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('users/create', ['title' => 'Tambah Pengguna', 'user' => []]);
    }

    public function store()
    {
        return $this->saveUser();
    }

    public function edit(int $id): string
    {
        $user = $this->findUser($id);
        unset($user['password']);

        return view('users/edit', ['title' => 'Edit Pengguna', 'user' => $user]);
    }

    public function update(int $id)
    {
        return $this->saveUser($id);
    }

    public function delete(int $id)
    {
        $user = $this->findUser($id);

        if ($this->isLastAdmin($user)) {
            return redirect()->to(site_url('users'))->with('error', 'Admin terakhir tidak dapat dihapus.');
        }

        (new UserModel())->delete($id);

        if ($id === (int) session()->get('user_id')) {
            session()->destroy();

            return redirect()->to(site_url('login'));
        }

        return redirect()->to(site_url('users'))->with('success', 'Pengguna berhasil dihapus.');
    }

    private function saveUser(?int $id = null)
    {
        $user = $id === null ? null : $this->findUser($id);
        $data = [
            'username' => trim((string) $this->request->getPost('username')),
            'role'     => (string) $this->request->getPost('role'),
            'password' => (string) $this->request->getPost('password'),
        ];
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|alpha_numeric_punct|is_unique[users.username,id,' . ($id ?? 0) . ']',
            'role'     => 'required|in_list[admin,staff]',
            'password' => ($id === null ? 'required' : 'permit_empty') . '|min_length[8]|max_length[72]',
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($user !== null && $data['role'] !== 'admin' && $this->isLastAdmin($user)) {
            return redirect()->back()->with('error', 'Role Admin terakhir tidak dapat diubah menjadi Staff.');
        }

        if ($data['password'] === '') {
            unset($data['password']);
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $model = new UserModel();
        $saved = $id === null ? $model->insert($data) : $model->update($id, $data);

        if (! $saved) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        if ($id === (int) session()->get('user_id') && $data['role'] !== 'admin') {
            return redirect()->to(site_url('dashboard'))->with('success', 'Pengguna berhasil diperbarui.');
        }

        return redirect()->to(site_url('users'))->with('success', $id === null ? 'Pengguna berhasil ditambahkan.' : 'Pengguna berhasil diperbarui.');
    }

    private function findUser(int $id): array
    {
        return (new UserModel())->find($id) ?? throw PageNotFoundException::forPageNotFound('Pengguna tidak ditemukan.');
    }

    private function isLastAdmin(array $user): bool
    {
        return $user['role'] === 'admin' && (new UserModel())->where('role', 'admin')->countAllResults() <= 1;
    }
}
