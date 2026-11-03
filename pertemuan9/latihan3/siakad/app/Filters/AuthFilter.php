<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Membatasi seluruh route admin pada session yang sudah login. */
class AuthFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = NULL)
	{
		if ( ! session()->has('user_id'))
		{
			return redirect()->to(site_url('login'))->with('error', lang('Siakad.login_required'));
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL)
	{
		$response->setHeader('Cache-Control', 'no-store, private');
	}
}
