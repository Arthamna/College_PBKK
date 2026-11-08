<?php
namespace App\Controllers;
class OrderController extends ShopController {
 protected function order(int $id): array {
  $o=(new \App\Models\OrderModel())->find($id); $u=$this->user();
  if (!$o || !$u || ($u['role']==='customer' && $o['user_id']!=$u['id'])) { $this->missing(); } return $o;
 }
 public function index() {
  $m=new \App\Models\OrderModel(); if ($this->user()['role']==='customer') { $m->where('user_id',session('user_id')); }
  return $this->page('orders',['orders'=>$m->orderBy('id','DESC')->findAll()]);
 }
 public function show(int $id) {
  $o=$this->order($id); return $this->page('order',['order'=>$o,'items'=>(new \App\Models\OrderItemModel())->where('order_id',$id)->findAll(),'payment'=>(new \App\Models\PaymentModel())->where('order_id',$id)->first()]);
 }
 public function submit(int $id) {
  $this->order($id); $file=null;
  try { $file=$this->upload('proof','payments'); (new \App\Services\OrderService(db_connect()))->change($id,(int)session('user_id'),'submit',$file); }
  catch (\Throwable $e) { if ($file) { unlink(WRITEPATH.'uploads/payments/'.$file); } if (!$e instanceof \DomainException) { throw $e; } return $this->fail($e->getMessage(),'/orders/'.$id); }
  return redirect()->to('/orders/'.$id)->with('message','Bukti pembayaran dikirim.');
 }
 public function proof(int $id) {
  $o=$this->order($id); if ($this->user()['role']==='operator') { return $this->response->setStatusCode(403)->setBody('Akses ditolak.'); }
  $p=(new \App\Models\PaymentModel())->where('order_id',$id)->first(); $path=WRITEPATH.'uploads/payments/'.basename($p['proof_file']??'');
  if (!$p || !$p['proof_file'] || !is_file($path)) { $this->missing(); }
  return $this->response->setHeader('Content-Type',mime_content_type($path))->setHeader('Cache-Control','private, no-store')->setHeader('X-Content-Type-Options','nosniff')->setBody(file_get_contents($path));
 }
}
