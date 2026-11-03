<?php

namespace App\Filters;

use App\Models\AdminModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = NULL)
	{
		$admin_id = session()->get('admin_id');

		if ($admin_id === NULL OR (new AdminModel())->find($admin_id) === NULL)
		{
			session()->remove(['admin_id', 'admin_name']);

			return redirect()->to('/admin/login')->with('error', lang('Shop.login_required'));
		}

		return NULL;
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL)
	{
		$response->setHeader('Cache-Control', 'no-store');
	}
}
