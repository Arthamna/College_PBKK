<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
class ShopAuth implements \CodeIgniter\Filters\FilterInterface {
 public function before(RequestInterface $request,$arguments=null) {
  $user=session('user_id')?(new \App\Models\UserModel())->find(session('user_id')):null;
  if (!$user || !$user['is_active']) { session()->remove('user_id'); return redirect()->to('/login'); }
  if ($arguments && !in_array($user['role'],$arguments,true)) { return service('response')->setStatusCode(403)->setBody('Akses ditolak.'); }
 }
 public function after(RequestInterface $request,ResponseInterface $response,$arguments=null) {}
}
