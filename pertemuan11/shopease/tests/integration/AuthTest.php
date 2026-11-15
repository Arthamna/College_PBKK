<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Enums\Role;
use App\Exceptions\ShopException;
use Config\Services;
use Tests\Support\ShopTestCase;

final class AuthTest extends ShopTestCase
{
    public function testRegistrationHashesPasswordAndForcesCustomerRole(): void
    {
        $id = Services::auth()->register('Siti', 'Siti@Example.com', 'rahasia-panjang', null);

        $user = $this->db->table('users')->where('id', $id)->get()->getRowArray();

        $this->assertSame('siti@example.com', $user['email']);
        $this->assertSame(Role::Customer->value, $user['role']);
        $this->assertNotSame('rahasia-panjang', $user['password_hash']);
        $this->assertTrue(password_verify('rahasia-panjang', $user['password_hash']));
    }

    public function testDuplicateEmailIsRejected(): void
    {
        $this->expectException(ShopException::class);

        Services::auth()->register('Budi 2', 'customer@shopease.test', 'rahasia-panjang', null);
    }

    public function testValidLoginReturnsUserWithRole(): void
    {
        $user = Services::auth()->attempt('admin@shopease.test', 'Admin#12345');

        $this->assertNotNull($user);
        $this->assertSame('administrator', $user['role']);
    }

    public function testWrongPasswordOrUnknownEmailIsRejected(): void
    {
        $this->assertNull(Services::auth()->attempt('admin@shopease.test', 'salah'));
        $this->assertNull(Services::auth()->attempt('nobody@shopease.test', 'Admin#12345'));
    }

    public function testInactiveUserCannotLogin(): void
    {
        $this->db->table('users')->where('email', 'customer@shopease.test')->update(['is_active' => 0]);

        $this->assertNull(Services::auth()->attempt('customer@shopease.test', 'Customer#12345'));
    }
}
