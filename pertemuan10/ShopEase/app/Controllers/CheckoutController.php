<?php
namespace App\Controllers;
use App\Models\AddressModel;
class CheckoutController extends ShopController {
 public function index() {
  if (!session('checkout_token')) { session()->set('checkout_token',bin2hex(random_bytes(32))); }
  $items=db_connect()->table('cart_items')->select('cart_items.quantity, products.name, products.price')->join('carts','carts.id = cart_items.cart_id')->join('products','products.id = cart_items.product_id')->where('carts.user_id',session('user_id'))->get()->getResultArray();
  $subtotal='0.00'; foreach ($items as $item) { $subtotal=bcadd($subtotal,bcmul($item['price'],(string)$item['quantity'],2),2); }
  $shipping=(string)env('shop.shippingCost','10000.00');
  return $this->page('checkout',['addresses'=>(new AddressModel())->where('user_id',session('user_id'))->findAll(),'token'=>session('checkout_token'),'items'=>$items,'subtotal'=>$subtotal,'shipping'=>$shipping,'total'=>bcadd($subtotal,$shipping,2)]);
 }
 public function address() {
  $data=[]; foreach (['recipient_name','phone','address','city','province','postal_code'] as $key) { $data[$key]=trim((string)$this->request->getPost($key)); }
  if (!$this->validateData($data,['recipient_name'=>'required|max_length[255]','phone'=>'required|max_length[30]','address'=>'required|max_length[2000]','city'=>'required|max_length[255]','province'=>'required|max_length[255]','postal_code'=>'required|max_length[10]'])) { return $this->fail(implode(' ',$this->validator->getErrors()),'/checkout'); }
  (new AddressModel())->insert($data+['user_id'=>session('user_id')]); return redirect()->to('/checkout');
 }
 public function store() {
  $token=(string)$this->request->getPost('token');
  $existing=db_connect()->table('orders')->where(['user_id'=>session('user_id'),'checkout_token'=>$token])->get()->getRowArray();
  if (!$existing && (!session('checkout_token') || !hash_equals(session('checkout_token'),$token))) { return $this->fail('Token checkout tidak valid.','/checkout'); }
  try { $id=(new \App\Services\CheckoutService(db_connect()))->checkout((int)session('user_id'),(int)$this->request->getPost('address_id'),$token); }
  catch (\DomainException $e) { return $this->fail($e->getMessage(),'/checkout'); }
  session()->remove('checkout_token'); return redirect()->to('/orders/'.$id);
 }
}
