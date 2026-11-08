<?php
namespace App\Controllers;
use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\ProductModel;
class CartController extends ShopController {
 private function cart(): array {
  $m=new CartModel(); $cart=$m->where('user_id',session('user_id'))->first();
  if (!$cart) { $id=$m->insert(['user_id'=>session('user_id')]); $cart=$m->find($id); } return $cart;
 }
 public function index() {
  $cart=$this->cart(); $items=db_connect()->table('cart_items')->select('cart_items.*, products.name, products.price, products.stock, products.status')->join('products','products.id = cart_items.product_id')->where('cart_id',$cart['id'])->get()->getResultArray();
  $total='0.00'; foreach ($items as &$item) { $item['subtotal']=bcmul($item['price'],(string)$item['quantity'],2); $total=bcadd($total,$item['subtotal'],2); } unset($item);
  return $this->page('cart',['items'=>$items,'total'=>$total]);
 }
 public function save(int $productId) {
  $q=(string)$this->request->getPost('quantity');
  if (!preg_match('/^[1-9][0-9]{0,8}$/D',$q)) { return $this->fail('Kuantitas harus bilangan bulat positif.','/cart'); }
  $cart=$this->cart(); $db=db_connect(); $db->transBegin();
  try {
   $db->query('SELECT id FROM '.$db->prefixTable('carts').' WHERE id = ? FOR UPDATE',[$cart['id']]);
   $p=(new ProductModel())->find($productId); $m=new CartItemModel(); $item=$m->where(['cart_id'=>$cart['id'],'product_id'=>$productId])->first();
   $quantity=(int)$q; if ($this->request->getPost('mode')==='add') { $quantity+=(int)($item['quantity']??0); }
   if (!$p || $p['status']!=='active' || $quantity>$p['stock']) { throw new \DomainException('Produk nonaktif atau stok tidak cukup.'); }
   $data=['cart_id'=>$cart['id'],'product_id'=>$productId,'quantity'=>$quantity];
   $item?$m->update($item['id'],$data):$m->insert($data);
   if (!$db->transStatus()) { throw new \DomainException('Keranjang gagal disimpan.'); } $db->transCommit();
  } catch (\Throwable $e) { $db->transRollback(); if (!$e instanceof \DomainException) { throw $e; } return $this->fail($e->getMessage(),'/cart'); }
  return redirect()->to('/cart');
 }
 public function remove(int $id) { $cart=$this->cart(); (new CartItemModel())->where(['cart_id'=>$cart['id'],'id'=>$id])->delete(); return redirect()->to('/cart'); }
}
