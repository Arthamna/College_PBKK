<?php

declare(strict_types=1);

namespace App\Filters;

use App\Enums\Role;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Server-side authentication + role authorization.
 * Usage: 'filter' => 'auth' (any user) or 'auth:administrator,operator'.
 * The user is re-read from the database on every request, so deactivation and
 * role changes take effect immediately.
 */
class AuthFilter implements FilterInterface
{
    /**
     * @param list<string>|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId  = $session->get('user_id');

        $user = is_int($userId) ? model(UserModel::class)->findActive($userId) : null;

        if ($user === null) {
            $session->destroy();
            session()->setFlashdata('error', 'Silakan masuk terlebih dahulu.');
            session()->set('intended', $request->getUri()->getPath());

            return redirect()->to(site_url('login'));
        }

        $allowed = array_map(
            static fn (string $role): ?Role => Role::tryFrom($role),
            $arguments ?? [],
        );

        if ($allowed !== [] && ! in_array(Role::from($user['role']), $allowed, true)) {
            return service('response')
                ->setStatusCode(403)
                ->setBody(view('errors/forbidden'));
        }

        return null;
    }

    /**
     * @param list<string>|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
