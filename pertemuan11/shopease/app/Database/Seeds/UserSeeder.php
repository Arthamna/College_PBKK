<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Demo accounts for local development only. Change before any real deployment. */
    private const array ACCOUNTS = [
        ['Administrator', 'admin@shopease.test', 'administrator', 'Admin#12345'],
        ['Operator Toko', 'operator@shopease.test', 'operator', 'Operator#12345'],
        ['Budi Customer', 'customer@shopease.test', 'customer', 'Customer#12345'],
    ];

    public function run(): void
    {
        $users = $this->db->table('users');
        $now   = date('Y-m-d H:i:s');

        foreach (self::ACCOUNTS as [$name, $email, $role, $password]) {
            if ($users->where('email', $email)->countAllResults() > 0) {
                continue;
            }

            $users->insert([
                'name'          => $name,
                'email'         => $email,
                'password_hash' => password_hash($password, PASSWORD_ARGON2ID),
                'role'          => $role,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}
