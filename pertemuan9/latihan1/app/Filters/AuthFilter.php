<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $id = session()->get('user_id');
        $user = $id ? (new UserModel())->find($id) : null;

        if ($user === null || ! in_array($user['role'], ['admin', 'staff'], true)) {
            session()->remove(['user_id', 'username', 'role']);

            return redirect()->to(site_url('login'))->with('error', 'Silakan login untuk melanjutkan.');
        }

        session()->set(['username' => $user['username'], 'role' => $user['role']]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Cache-Control', 'no-store, private');
    }
}
