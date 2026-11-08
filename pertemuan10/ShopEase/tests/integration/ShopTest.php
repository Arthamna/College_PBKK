<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use App\Services\CheckoutService;
use App\Services\OrderService;
final class ShopTest extends CIUnitTestCase {
 use FeatureTestTrait;
 private $shopDb;
 protected function setUp(): void {
  parent::setUp();
  $this->shopDb=db_connect();
  if ($this->shopDb->DBDriver!=='MySQLi' || !str_ends_with($this->shopDb->database,'_test')) { $this->markTestSkipped('Requires dedicated MySQL database ending in _test.'); }
  require_once APPPATH.'Database/Migrations/2026-11-05-000001_CreateShop.php';
  $migration=new \App\Database\Migrations\CreateShop(\Config\Database::forge()); $migration->down(); $migration->up();
  $now=date('Y-m-d H:i:s');
  foreach (['customer','administrator','operator','customer'] as $i=>$role) { $this->shopDb->table('users')->insert(['id'=>$i+1,'name'=>$role,'email'=>'user'.$i.'@example.test','password_hash'=>password_hash('TestPassword42',PASSWORD_DEFAULT),'role'=>$role,'is_active'=>1]); }
  $this->shopDb->table('categories')->insert(['id'=>1,'name'=>'Demo','slug'=>'demo','description'=>'','is_active'=>1]);
  $this->shopDb->table('products')->insert(['id'=>1,'category_id'=>1,'name'=>'Buku','slug'=>'buku','description'=>'Contoh','price'=>'15000.25','stock'=>2,'image'=>'','status'=>'active']);
  foreach ([1,4] as $id) {
   $this->shopDb->table('addresses')->insert(['id'=>$id,'user_id'=>$id,'recipient_name'=>'Contoh','phone'=>'000','address'=>'Jalan contoh','city'=>'Kota','province'=>'Provinsi','postal_code'=>'12345']);
   $this->shopDb->table('carts')->insert(['id'=>$id,'user_id'=>$id]);
   $this->shopDb->table('cart_items')->insert(['cart_id'=>$id,'product_id'=>1,'quantity'=>2]);
  }
 }
 private function checkout(int $id=1): int { return (new CheckoutService($this->shopDb))->checkout($id,$id,str_repeat((string)$id,64)); }
 public function testCheckoutSnapshotsAndReplay(): void {
  $id=$this->checkout(); $this->assertSame($id,$this->checkout());
  $order=$this->shopDb->table('orders')->where('id',$id)->get()->getRowArray();
  $this->assertSame('30000.50',$order['subtotal']); $this->assertSame('40000.50',$order['total']);
  $this->assertSame(0,(int)$this->shopDb->table('products')->get()->getRow()->stock);
  $this->shopDb->table('products')->where('id',1)->update(['name'=>'Changed','price'=>'999']);
  $this->shopDb->table('addresses')->where('id',1)->update(['address'=>'Changed']);
  $line=$this->shopDb->table('order_items')->get()->getRowArray(); $this->assertSame('Buku',$line['product_name']); $this->assertSame('15000.25',$line['price']);
  $this->assertStringContainsString('Jalan contoh',$order['address_snapshot']);
 }
 public function testExhaustedStockRejectsSecondBuyer(): void {
  $this->checkout(); try { $this->checkout(4); $this->fail('Should reject'); } catch (\DomainException $e) { $this->assertStringContainsString('stok',$e->getMessage()); }
  $this->assertSame(1,$this->shopDb->table('orders')->countAllResults());
  $this->assertSame(1,$this->shopDb->table('cart_items')->where('cart_id',4)->countAllResults());
 }
 public function testForeignAddressRollsBack(): void {
  try { (new CheckoutService($this->shopDb))->checkout(1,4,str_repeat('a',64)); $this->fail('Should reject'); } catch (\DomainException $e) { $this->assertStringContainsString('Alamat',$e->getMessage()); }
  $this->assertSame(0,$this->shopDb->table('orders')->countAllResults());
  $this->assertSame(2,(int)$this->shopDb->table('products')->get()->getRow()->stock);
 }
 public function testPaymentRejectResubmitVerify(): void {
  $id=$this->checkout(); $s=new OrderService($this->shopDb);
  $s->change($id,1,'submit','first.png'); $s->change($id,2,'reject','Tidak terbaca'); $s->change($id,1,'submit','second.png'); $s->change($id,2,'verify');
  $this->assertSame('processing',$this->shopDb->table('orders')->get()->getRow()->order_status);
  $this->assertSame('verified',$this->shopDb->table('payments')->get()->getRow()->payment_status);
  $this->expectException(\DomainException::class); $s->change($id,2,'verify');
 }
 public function testCancellationReturnsStockOnlyOnce(): void {
  $id=$this->checkout(); $s=new OrderService($this->shopDb); $s->change($id,2,'cancel');
  try { $s->change($id,2,'cancel'); $this->fail('Should reject'); } catch (\DomainException $e) { $this->assertStringContainsString('dibatalkan',$e->getMessage()); }
  $this->assertSame(2,(int)$this->shopDb->table('products')->get()->getRow()->stock);
 }
 public function testOperatorCannotVerify(): void {
  $id=$this->checkout(); $s=new OrderService($this->shopDb); $s->change($id,1,'submit','proof.png');
  $this->expectException(\DomainException::class); $s->change($id,3,'verify');
 }
 public function testPagesAndOwnership(): void {
  $id=$this->checkout();
  $this->get('/products')->assertStatus(200);
  $this->get('/products/1')->assertStatus(200);
  $this->get('/login')->assertStatus(200);
  $this->withSession(['user_id'=>1])->get('/cart')->assertStatus(200);
  $this->withSession(['user_id'=>1])->get('/checkout')->assertStatus(200);
  $this->withSession(['user_id'=>1])->get('/orders/'.$id)->assertStatus(200);
  try { $this->withSession(['user_id'=>4])->get('/orders/'.$id); $this->fail('Foreign order must be hidden'); } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) { $this->assertSame(404,$e->getCode()); }
  $this->withSession(['user_id'=>1])->get('/admin/products')->assertStatus(403);
  $this->withSession(['user_id'=>2])->get('/admin/products')->assertStatus(200);
  $this->withSession(['user_id'=>2])->get('/admin/products/1/edit')->assertStatus(200);
  $this->withSession(['user_id'=>2])->get('/admin/categories/new')->assertStatus(200);
  $this->withSession(['user_id'=>3])->get('/admin/orders/'.$id)->assertStatus(200);
 }
 public function testCartAndRegistrationForms(): void {
  $result=$this->post('/register',['name'=>'Baru','email'=>'new@example.test','password'=>'Password123',csrf_token()=>csrf_hash()]); $result->assertRedirectTo('/login');
  $user=$this->shopDb->table('users')->where('email','new@example.test')->get()->getRowArray(); $this->assertNotNull($user); $this->assertSame('customer',$user['role']); $this->assertTrue(password_verify('Password123',$user['password_hash']));
  $this->post('/login',['email'=>'new@example.test','password'=>'Password123',csrf_token()=>csrf_hash()])->assertRedirectTo('/products');
  $this->withSession(['user_id'=>1])->post('/cart/1',['quantity'=>'1',csrf_token()=>csrf_hash()])->assertRedirectTo('/cart');
  $this->assertSame(1,(int)$this->shopDb->table('cart_items')->where('cart_id',1)->get()->getRow()->quantity);
  $this->withSession(['user_id'=>1])->post('/cart/1',['quantity'=>'-1',csrf_token()=>csrf_hash()])->assertRedirectTo('/cart');
  $this->assertSame(1,(int)$this->shopDb->table('cart_items')->where('cart_id',1)->get()->getRow()->quantity);
  $this->withSession(['user_id'=>1])->get('/cart')->assertSee('15000.25');
  $this->withSession(['user_id'=>1])->get('/checkout')->assertSee('25000.25');
 }
 public function testFailureAfterOrderInsertRollsBack(): void {
  $table=$this->shopDb->prefixTable('order_items');
  $this->shopDb->query("CREATE TRIGGER fail_shop_item BEFORE INSERT ON $table FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'test failure'");
  try {
   try { $this->checkout(); $this->fail('Should roll back'); } catch (\Throwable $e) { $this->assertNotInstanceOf(\PHPUnit\Framework\AssertionFailedError::class,$e); }
   $this->assertSame(0,$this->shopDb->table('orders')->countAllResults());
   $this->assertSame(2,(int)$this->shopDb->table('products')->get()->getRow()->stock);
   $this->assertSame(1,$this->shopDb->table('cart_items')->where('cart_id',1)->countAllResults());
  } finally { $this->shopDb->query('DROP TRIGGER fail_shop_item'); }
 }

}
