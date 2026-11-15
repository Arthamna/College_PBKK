<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Role;
use App\Exceptions\ShopException;
use App\Models\UserModel;

class AuthService
{
    /** Verified against when the email is unknown, so timing does not reveal valid accounts. */
    private const string DUMMY_HASH = '$argon2id$v=19$m=65536,t=4,p=1$c29tZXNhbHRzb21lc2FsdA$ZmFrZWhhc2hmYWtlaGFzaGZha2VoYXNoZmFrZWhhc2g';

    public function __construct(private readonly UserModel $users)
    {
    }

    /**
     * Registers a customer. The role is always forced server-side; it is never read from input.
     */
    public function register(string $name, string $email, string $password, ?string $phone): int
    {
        $id = $this->users->insert([
            'name'          => trim($name),
            'email'         => mb_strtolower(trim($email)),
            'password_hash' => password_hash($password, PASSWORD_ARGON2ID),
            'phone'         => $phone !== null && $phone !== '' ? trim($phone) : null,
            'role'          => Role::Customer->value,
            'is_active'     => 1,
        ], true);

        if ($id === false) {
            throw new ShopException('Registrasi gagal: ' . implode(' ', $this->users->errors()));
        }

        return (int) $id;
    }

    /**
     * @return array<string, mixed>|null the user row when credentials are valid
     */
    public function attempt(string $email, string $password): ?array
    {
        $user = $this->users->findByEmail($email);
        $hash = $user['password_hash'] ?? self::DUMMY_HASH;

        $valid = password_verify($password, $hash);

        if ($user === null || ! $valid || (int) $user['is_active'] !== 1) {
            return null;
        }

        if (password_needs_rehash($hash, PASSWORD_ARGON2ID)) {
            $this->users->update((int) $user['id'], [
                'password_hash' => password_hash($password, PASSWORD_ARGON2ID),
            ]);
        }

        return $user;
    }
}
