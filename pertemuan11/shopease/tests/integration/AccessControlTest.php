<?php

declare(strict_types=1);

namespace Tests\Integration;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\ShopTestCase;

final class AccessControlTest extends ShopTestCase
{
    use FeatureTestTrait;

    private function asRole(string $email): array
    {
        $user = $this->db->table('users')->where('email', $email)->get()->getRowArray();

        return ['user_id' => (int) $user['id'], 'name' => $user['name'], 'role' => $user['role']];
    }

    public function testGuestIsRedirectedToLogin(): void
    {
        $this->get('admin')->assertRedirectTo(site_url('login'));
        $this->get('cart')->assertRedirectTo(site_url('login'));
        $this->get('orders')->assertRedirectTo(site_url('login'));
    }

    public function testCustomerGetsForbiddenOnAdminPages(): void
    {
        $session = $this->asRole('customer@shopease.test');

        foreach (['admin', 'admin/orders', 'admin/products', 'admin/categories', 'admin/reports', 'admin/customers'] as $path) {
            $this->withSession($session)->get($path)->assertStatus(403);
        }
    }

    public function testOperatorCanSeeOrdersButNotAdministratorPages(): void
    {
        $session = $this->asRole('operator@shopease.test');

        $this->withSession($session)->get('admin/orders')->assertStatus(200);
        $this->withSession($session)->get('admin/categories')->assertStatus(403);
        $this->withSession($session)->get('admin/reports')->assertStatus(403);
        $this->withSession($session)->get('admin/customers')->assertStatus(403);
        $this->withSession($session)->get('admin/products/new')->assertStatus(403);
    }

    public function testAdministratorCanOpenAllAdminPages(): void
    {
        $session = $this->asRole('admin@shopease.test');

        foreach (['admin', 'admin/orders', 'admin/products', 'admin/categories', 'admin/reports', 'admin/customers'] as $path) {
            $this->withSession($session)->get($path)->assertStatus(200);
        }
    }

    public function testStaffCannotUseCustomerCart(): void
    {
        $this->withSession($this->asRole('admin@shopease.test'))->get('cart')->assertStatus(403);
    }

    public function testPublicCatalogAndSearchWork(): void
    {
        $this->get('/')->assertStatus(200);
        $catalog = $this->get('products');
        $catalog->assertStatus(200);
        $catalog->assertSee('Panduan CodeIgniter 4 Lengkap');

        $empty = $this->get('products?q=zzzz-tidak-ada');
        $empty->assertStatus(200);
        $empty->assertSee('Tidak ada produk');

        $detail = $this->get('products/panduan-codeigniter-4');
        $detail->assertStatus(200);
        $detail->assertSee('Rp89.000');
    }

    public function testOutputIsEscaped(): void
    {
        $this->db->table('products')->where('slug', 'panduan-codeigniter-4')->update(['name' => '<script>alert(1)</script>']);

        $body = (string) $this->get('products')->getBody();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $body);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $body);
    }
}
