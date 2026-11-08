<?php
namespace App\Controllers\Admin;
class OrderController extends \App\Controllers\OrderController {
 public function change(int $id) {
  try { (new \App\Services\OrderService(db_connect()))->change($id,(int)session('user_id'),(string)$this->request->getPost('action'),trim((string)$this->request->getPost('reason'))); }
  catch (\DomainException $e) { return $this->fail($e->getMessage(),'/admin/orders/'.$id); }
  return redirect()->to('/admin/orders/'.$id)->with('message','Status diperbarui.');
 }
}
