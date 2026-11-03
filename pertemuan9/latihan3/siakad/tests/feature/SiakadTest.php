<?php

use App\Database\Seeds\MahasiswaSeeder;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\TestResponse;
use Config\Services;

/** Menguji alur HTTP dengan migration aplikasi dan database testing terpisah. */
final class SiakadTest extends CIUnitTestCase
{
	use DatabaseTestTrait;
	use FeatureTestTrait;

	protected $namespace = 'App';
	protected $refresh = TRUE;
	private int $admin_id;

	protected function setUp(): void
	{
		parent::setUp();
		$user_model = new UserModel($this->db);
		$this->admin_id = (int) $user_model->insert([
			'nama' => 'Administrator Uji',
			'username' => 'admin_uji',
			'password_hash' => password_hash('PasswordUji2026!', PASSWORD_DEFAULT),
		]);
	}

	public function test_halaman_login_dan_redirect_awal(): void
	{
		$this->get('/')->assertRedirectTo('/login');
		$result = $this->get('/login');
		$result->assertOK();
		$result->assertSeeElement('#username');
		$this->assertStringContainsString('name="csrf_test_name"', $result->getBody());
	}

	public function test_halaman_admin_memerlukan_login(): void
	{
		foreach (['/admin', '/admin/mahasiswa', '/admin/mahasiswa/create', '/admin/mahasiswa/1/edit'] as $path)
		{
			$this->withSession([])->get($path)->assertRedirectTo('/login');
		}
	}

	public function test_perubahan_data_memerlukan_login(): void
	{
		foreach (['/admin/mahasiswa', '/admin/mahasiswa/1/update', '/admin/mahasiswa/1/delete', '/logout'] as $path)
		{
			$this->withSession([]);
			$this->_post_csrf($path, $this->_mahasiswa())->assertRedirectTo('/login');
		}

		$this->assertSame(0, $this->db->table('mahasiswa')->countAllResults());
	}

	public function test_login_berhasil_dan_password_tidak_masuk_session(): void
	{
		$result = $this->_post_csrf('/login', ['username' => 'admin_uji', 'password' => 'PasswordUji2026!']);
		$result->assertRedirectTo('/admin');
		$result->assertSessionHas('user_id', $this->admin_id);
		$result->assertSessionMissing('password');
		$result->assertSessionMissing('password_hash');

		$this->withSession()->get('/login')->assertRedirectTo('/admin');
	}

	public function test_login_salah_tidak_membentuk_session_admin(): void
	{
		$result = $this->_post_csrf('/login', ['username' => 'admin_uji', 'password' => 'salah']);
		$result->assertRedirectTo('/login');
		$result->assertSessionMissing('user_id');
		$this->assertArrayNotHasKey('_ci_old_input', $_SESSION);
	}

	public function test_logout_mengakhiri_akses_admin(): void
	{
		$this->_login_session();
		$result = $this->_post_csrf('/logout');
		$result->assertRedirectTo('/login');
		$this->withSession()->get('/admin')->assertRedirectTo('/login');
	}

	public function test_csrf_menolak_request_tanpa_token(): void
	{
		$this->_login_session();
		$this->expectException(SecurityException::class);
		$this->post('/admin/mahasiswa', $this->_mahasiswa());
	}

	public function test_tambah_mahasiswa_dan_dashboard(): void
	{
		$this->_login_session();
		$this->_post_csrf('/admin/mahasiswa', $this->_mahasiswa() + ['id' => 999, 'password_hash' => 'injected'])
			->assertRedirectTo('/admin/mahasiswa');
		$this->seeInDatabase('mahasiswa', ['nim' => '0012345', 'nama' => 'Mahasiswa Uji']);
		$this->dontSeeInDatabase('mahasiswa', ['id' => 999]);
		$result = $this->get('/admin');
		$result->assertOK();
		$result->assertSee('Mahasiswa Uji');
		$result->assertSee('1', '.stat-number');
		$this->assertStringContainsString('no-store', $result->response()->getHeaderLine('Cache-Control'));
	}

	public function test_form_tambah_dan_edit_menampilkan_data(): void
	{
		$this->_login_session();
		$this->get('/admin/mahasiswa/create')->assertOK();
		$id = (new MahasiswaModel($this->db))->insert($this->_mahasiswa());
		$result = $this->get('/admin/mahasiswa/' . $id . '/edit');
		$result->assertOK();
		$result->assertSeeInField('nim', '0012345');
	}

	public function test_validasi_mempertahankan_input_dan_tidak_menyimpan(): void
	{
		$this->_login_session();
		$input = array_replace($this->_mahasiswa(), ['nama' => '', 'email' => 'bukan-email', 'angkatan' => '1800']);
		$result = $this->_post_csrf('/admin/mahasiswa', $input);
		$result->assertRedirectTo('/admin/mahasiswa/create');
		$result->assertSessionHas('errors');
		$this->assertSame(0, $this->db->table('mahasiswa')->countAllResults());

		$result = $this->withSession()->get('/admin/mahasiswa/create');
		$result->assertOK();
		$result->assertSeeInField('nim', '0012345');
		$result->assertSee('wajib diisi');
		$result->assertSee('alamat email yang valid');
	}

	public function test_nim_duplikat_ditolak_dan_edit_nim_sendiri_diizinkan(): void
	{
		$this->_login_session();
		$id = (new MahasiswaModel($this->db))->insert($this->_mahasiswa());
		$this->_post_csrf('/admin/mahasiswa', $this->_mahasiswa())->assertRedirectTo('/admin/mahasiswa/create');
		$this->assertSame(1, $this->db->table('mahasiswa')->countAllResults());

		$input = array_replace($this->_mahasiswa(), ['nama' => 'Nama Diperbarui', 'id' => 999]);
		$this->_post_csrf('/admin/mahasiswa/' . $id . '/update', $input)->assertRedirectTo('/admin/mahasiswa');
		$this->seeInDatabase('mahasiswa', ['id' => $id, 'nim' => '0012345', 'nama' => 'Nama Diperbarui']);
	}

	public function test_edit_tidak_bisa_memakai_nim_mahasiswa_lain(): void
	{
		$this->_login_session();
		$model = new MahasiswaModel($this->db);
		$id = $model->insert($this->_mahasiswa());
		$model->insert(array_replace($this->_mahasiswa(), ['nim' => '0022222']));
		$input = array_replace($this->_mahasiswa(), ['nim' => '0022222']);
		$this->_post_csrf('/admin/mahasiswa/' . $id . '/update', $input)
			->assertRedirectTo('/admin/mahasiswa/' . $id . '/edit');
		$this->seeInDatabase('mahasiswa', ['id' => $id, 'nim' => '0012345']);
	}

	public function test_hapus_mahasiswa_dan_larangan_get(): void
	{
		$this->_login_session();
		$id = (new MahasiswaModel($this->db))->insert($this->_mahasiswa());
		$this->_assert_not_found(fn () => $this->get('/admin/mahasiswa/' . $id . '/delete'));
		$this->seeInDatabase('mahasiswa', ['id' => $id]);
		$this->_post_csrf('/admin/mahasiswa/' . $id . '/delete')->assertRedirectTo('/admin/mahasiswa');
		$this->dontSeeInDatabase('mahasiswa', ['id' => $id]);
	}

	public function test_id_tidak_ditemukan_menghasilkan_404(): void
	{
		$this->_login_session();
		$this->_assert_not_found(fn () => $this->get('/admin/mahasiswa/999/edit'));
		$this->_assert_not_found(fn () => $this->_post_csrf('/admin/mahasiswa/999/update', $this->_mahasiswa()));
		$this->_assert_not_found(fn () => $this->_post_csrf('/admin/mahasiswa/999/delete'));
	}

	public function test_pencarian_pagination_dan_escaping_output(): void
	{
		$this->_login_session();
		$model = new MahasiswaModel($this->db);

		for ($nomor = 1; $nomor <= 12; $nomor++)
		{
			$model->insert(array_replace($this->_mahasiswa(), [
				'nim' => sprintf('2026%04d', $nomor),
				'nama' => 'Mahasiswa ' . $nomor,
			]));
		}

		$model->insert(array_replace($this->_mahasiswa(), ['nama' => '<script>alert(1)</script>']));
		$result = $this->get('/admin/mahasiswa');
		$result->assertOK();
		$this->assertSame(10, substr_count($result->getBody(), 'data-confirm='));
		$this->assertStringContainsString('&lt;script&gt;', $result->getBody());
		$this->assertStringNotContainsString('<script>alert(1)</script>', $result->getBody());

		// Setiap request browser memiliki instance Pager baru.
		Services::resetSingle('pager');
		$result = $this->get('/admin/mahasiswa?page_mahasiswa=2');
		$result->assertOK();
		$this->assertSame(3, substr_count($result->getBody(), 'data-confirm='));

		Services::resetSingle('pager');
		$result = $this->get('/admin/mahasiswa?q=20260012');
		$result->assertSee('Mahasiswa 12');
		$this->assertSame(1, substr_count($result->getBody(), 'data-confirm='));
		Services::resetSingle('pager');
		$this->get('/admin/mahasiswa?q=tidakada')->assertSee('Tidak ada mahasiswa yang sesuai pencarian.');
	}

	public function test_seeder_mahasiswa_tidak_menggandakan_data(): void
	{
		$this->seed(MahasiswaSeeder::class);
		$this->seed(MahasiswaSeeder::class);
		$this->assertSame(5, $this->db->table('mahasiswa')->countAllResults());
	}

	private function _login_session(): void
	{
		$this->withSession(['user_id' => $this->admin_id, 'nama' => 'Administrator Uji', 'username' => 'admin_uji']);
	}

	private function _assert_not_found(callable $request): void
	{
		try
		{
			$request();
			$this->fail('Request seharusnya menghasilkan 404.');
		}
		catch (PageNotFoundException $exception)
		{
			$this->assertSame(404, $exception->getCode());
		}
	}

	private function _post_csrf(string $path, array $input = []): TestResponse
	{
		$security = service('security');
		$token_name = $security->getTokenName();
		$token_hash = $security->getHash();
		$this->session[$token_name] = $token_hash;

		return $this->post($path, $input + [$token_name => $token_hash]);
	}

	private function _mahasiswa(): array
	{
		return [
			'nim' => '0012345',
			'nama' => 'Mahasiswa Uji',
			'program_studi' => 'Informatika',
			'angkatan' => '2026',
			'email' => 'mahasiswa@example.test',
			'alamat' => 'Surabaya',
		];
	}
}
