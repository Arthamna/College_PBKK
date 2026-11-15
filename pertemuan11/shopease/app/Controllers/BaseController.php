<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ShopException;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

abstract class BaseController extends Controller
{
    protected $helpers = ['url', 'form', 'shop'];

    /**
     * @return array{id:int,name:string,role:\App\Enums\Role}
     */
    protected function user(): array
    {
        return current_user() ?? throw new \LogicException('Route is not protected by the auth filter.');
    }

    protected function back(string $path, ?string $success = null, ?string $error = null): RedirectResponse
    {
        $redirect = redirect()->to(site_url($path));

        if ($success !== null) {
            $redirect->with('success', $success);
        }

        if ($error !== null) {
            $redirect->with('error', $error);
        }

        return $redirect;
    }

    protected function failWith(string $path, ShopException $e, bool $withInput = false): RedirectResponse
    {
        $redirect = redirect()->to(site_url($path))->with('error', $e->getMessage());

        return $withInput ? $redirect->withInput() : $redirect;
    }

    /**
     * @return array<string, string>
     */
    protected function validationErrors(): array
    {
        return $this->validator?->getErrors() ?? [];
    }
}
