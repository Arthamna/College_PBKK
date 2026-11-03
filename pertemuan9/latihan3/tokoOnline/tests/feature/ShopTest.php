<?php

namespace Tests\Feature;

use App\Database\Seeds\ProductSeeder;
use App\Libraries\Cart;
use App\Models\AdminModel;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\TestResponse;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;
use DomainException;

/** @internal */
final class ShopTest extends CIUnitTestCase
{
	use DatabaseTestTrait;
	use FeatureTestTrait;

	protected $namespace = 'App';
	protected $refresh = TRUE;

	protected function setUp(): void
	{
		parent::setUp();
		$_SESSION = [];
	}

	public function test_catalog_renders_empty_state_and_pagination(): void
	{
		$this->get('/')->assertSee('Koleksi sedang disiapkan');
		$this->seed(ProductSeeder::class);
		$this->seed(ProductSeeder::class);
		$this->assertSame(13, (new ProductModel())->countAllResults());
		$result = $this->get('/');
		$result->assertStatus(200);
		$result->assertSee('13 produk dalam katalog');
		$result->assertSee('page=2');
		Services::resetSingle('pager');
		$this->get('/?page=2')->assertSee('Tas Kanvas Harian');
	}

	public function test_detail_escapes_product_text_and_missing_product_returns_404(): void
	{
		$product_id = $this->_product(['name' => '<script>alert(1)</script>']);
		$result = $this->get('/products/' . $product_id);
		$result->assertStatus(200);
		$this->assertStringContainsString('&lt;script&gt;', $result->response()->getBody());
		$this->expectException(PageNotFoundException::class);
		$this->get('/products/999999');
	}

	public function test_guest_cannot_open_admin_or_delete_products(): void
	{
		$product_id = $this->_product();
		$this->get('/admin/products')->assertRedirectTo(site_url('admin/login'));
		$this->_post('/admin/products/' . $product_id . '/delete')->assertRedirectTo(site_url('admin/login'));
		$this->assertNotNull((new ProductModel())->find($product_id));
		$this->expectException(PageNotFoundException::class);
		$this->get('/admin/products/' . $product_id . '/delete');
	}

	public function test_admin_can_login_and_logout_without_losing_cart(): void
	{
		$admin_id = $this->_admin();
		$result = $this->_post('/admin/login', ['email' => 'ADMIN@example.com', 'password' => 'TestPassword123!']);
		$result->assertRedirectTo(site_url('admin/products'));
		$result->assertSessionHas('admin_id', $admin_id);
		$result = $this->_post('/admin/logout', [], ['admin_id' => $admin_id, 'cart' => [1 => 2]]);
		$result->assertSessionMissing('admin_id');
		$result->assertSessionHas('cart', [1 => 2]);
	}

	public function test_bad_login_does_not_store_password(): void
	{
		$this->_admin();
		$result = $this->_post('/admin/login', ['email' => 'admin@example.com', 'password' => 'incorrect']);
		$result->assertRedirectTo(site_url('admin/login'));
		$result->assertSessionMissing('admin_id');
		$this->assertStringNotContainsString('incorrect', serialize($_SESSION));
	}

	public function test_admin_can_create_update_and_delete_product(): void
	{
		$admin = ['admin_id' => $this->_admin()];
		$data = ['name' => 'Produk baru', 'description' => 'Catatan', 'price' => '10000', 'stock' => '5'];
		$this->_post('/admin/products', $data, $admin)->assertRedirectTo(site_url('admin/products'));
		$product = (new ProductModel())->where('name', 'Produk baru')->first();
		$this->assertNotNull($product);
		$this->assertSame(10000, (int) $product['price']);
		$this->withSession($admin)->get('/admin/products/' . $product['id'] . '/edit')->assertSeeInField('name', 'Produk baru');
		$data['stock'] = '0';
		$data['price'] = '15000';
		$this->_post('/admin/products/' . $product['id'] . '/update', $data, $admin)->assertRedirectTo(site_url('admin/products'));
		$this->assertSame(0, (int) (new ProductModel())->find($product['id'])['stock']);
		$this->_post('/admin/products/' . $product['id'] . '/delete', [], $admin)->assertRedirectTo(site_url('admin/products'));
		$this->assertNull((new ProductModel())->find($product['id']));
	}

	public function test_invalid_product_is_rejected_with_localized_errors(): void
	{
		$result = $this->_post('/admin/products', [
			'name' => ' ', 'description' => '', 'price' => '-5', 'stock' => '1.5',
		], ['admin_id' => $this->_admin()]);
		$result->assertRedirectTo(site_url('admin/products/create'));
		$result->assertSessionHas('errors');
		$this->assertSame(0, (new ProductModel())->countAllResults());
		$this->assertSame(lang('Shop.price_invalid'), session('errors')['price']);
	}

	public function test_cart_merges_additions_and_never_changes_stock(): void
	{
		$product_id = $this->_product();
		$cart = new Cart();
		$cart->add($product_id, 2);
		$cart->add($product_id, 1);
		$this->assertSame([$product_id => 3], session('cart'));
		$this->assertSame(30000, $cart->summary()['total']);
		$this->assertSame(5, (int) (new ProductModel())->find($product_id)['stock']);
	}

	public function test_cart_rejects_additions_above_stock(): void
	{
		$product_id = $this->_product();
		$cart = new Cart();
		$cart->add($product_id, 4);
		$this->expectException(DomainException::class);
		$cart->add($product_id, 2);
	}

	public function test_cart_uses_current_prices_and_reconciles_stock(): void
	{
		$product_id = $this->_product();
		$cart = new Cart();
		$cart->add($product_id, 4);
		(new ProductModel())->update($product_id, ['price' => 20000, 'stock' => 2]);
		$summary = $cart->summary();
		$this->assertTrue($summary['adjusted']);
		$this->assertSame(40000, $summary['total']);
		$this->assertSame([$product_id => 2], session('cart'));
		(new ProductModel())->delete($product_id);
		$this->assertSame([], $cart->summary()['items']);
		$this->assertSame([], session('cart'));
	}

	public function test_cart_removes_sold_out_items(): void
	{
		$product_id = $this->_product();
		$cart = new Cart();
		$cart->add($product_id, 2);
		(new ProductModel())->update($product_id, ['stock' => 0]);
		$this->assertSame(0, $cart->summary()['count']);
		$this->assertSame([], session('cart'));
	}

	public function test_cart_http_add_update_remove_and_clear(): void
	{
		$product_id = $this->_product();
		$result = $this->_post('/cart/add', ['product_id' => $product_id, 'quantity' => 2]);
		$result->assertSessionHas('cart', [$product_id => 2]);
		$result = $this->_post('/cart/update/' . $product_id, ['quantity' => 3], ['cart' => [$product_id => 2]]);
		$result->assertSessionHas('cart', [$product_id => 3]);
		$this->withSession(['cart' => [$product_id => 3]])->get('/cart')->assertSee('Rp 30.000');
		$result = $this->_post('/cart/remove/' . $product_id, [], ['cart' => [$product_id => 3]]);
		$result->assertSessionHas('cart', []);
		$result = $this->_post('/cart/clear', [], ['cart' => [$product_id => 3]]);
		$result->assertSessionMissing('cart');
	}

	public function test_cart_rejects_invalid_quantities_and_ignores_submitted_price(): void
	{
		$product_id = $this->_product();
		$this->_post('/cart/add', ['product_id' => $product_id, 'quantity' => '-2'])->assertSessionMissing('cart');
		$this->_post('/cart/add', ['product_id' => $product_id, 'quantity' => ['2']])->assertSessionMissing('cart');
		$result = $this->_post('/cart/add', ['product_id' => $product_id, 'quantity' => 1, 'price' => 1]);
		$result->assertSessionHas('cart', [$product_id => 1]);
		$this->assertSame(10000, (new Cart())->summary()['total']);
	}

	public function test_missing_and_sold_out_products_cannot_be_added(): void
	{
		$product_id = $this->_product(['stock' => 0]);
		$this->_post('/cart/add', ['product_id' => $product_id, 'quantity' => 1])->assertSessionMissing('cart');
		$this->_post('/cart/add', ['product_id' => 999999, 'quantity' => 1])->assertSessionMissing('cart');
	}

	private function _product(array $overrides = []): int
	{
		return (int) (new ProductModel())->insert(array_replace([
			'name' => 'Produk uji', 'description' => 'Deskripsi', 'price' => 10000, 'stock' => 5,
		], $overrides));
	}

	private function _admin(): int
	{
		return (int) (new AdminModel())->insert([
			'name' => 'Admin Uji', 'email' => 'admin@example.com',
			'password_hash' => password_hash('TestPassword123!', PASSWORD_DEFAULT),
		]);
	}

	private function _post(string $url, array $data = [], array $session = []): TestResponse
	{
		$security = service('security');
		$data[$security->getTokenName()] = $security->getHash();
		$session[config('Security')->tokenName] = $security->getHash();

		return $this->withSession($session)->post($url, $data);
	}
}
