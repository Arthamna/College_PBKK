<?php

declare(strict_types=1);

namespace App\Filters;

use App\Enums\Role;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Keeps login/register for guests; signed-in users are sent to their home.
 */
class GuestFilter implements FilterInterface
{
    /**
     * @param list<string>|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = current_user();

        if ($user === null) {
            return null;
        }

        return redirect()->to(site_url($user['role'] === Role::Customer ? '/' : 'admin'));
    }

    /**
     * @param list<string>|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
