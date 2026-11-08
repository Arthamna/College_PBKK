<?php
namespace App\Controllers;
use App\Models\UserModel;
class AuthController extends ShopController {
 public function login() { return $this->page('auth',['register'=>false]); }
 public function register() { return $this->page('auth',['register'=>true]); }
 public function authenticate() {
  $email=strtolower(trim((string)$this->request->getPost('email')));
  $user=(new UserModel())->where('email',$email)->first();
  if (!$user || !$user['is_active'] || !password_verify((string)$this->request->getPost('password'),$user['password_hash'])) { return $this->fail('Email atau password salah.','/login'); }
  session()->regenerate(true); session()->set('user_id',$user['id']);
  return redirect()->to($user['role']==='customer'?'/products':'/admin/orders');
 }
 public function store() {
  $data=['name'=>trim((string)$this->request->getPost('name')),'email'=>strtolower(trim((string)$this->request->getPost('email'))),'password'=>(string)$this->request->getPost('password')];
  if (!$this->validateData($data,['name'=>'required|max_length[255]','email'=>'required|valid_email|max_length[255]|is_unique[users.email]','password'=>'required|min_length[8]|max_length[200]'])) { return $this->fail(implode(' ',$this->validator->getErrors()),'/register'); }
  (new UserModel())->insert(['name'=>$data['name'],'email'=>$data['email'],'password_hash'=>password_hash($data['password'],PASSWORD_DEFAULT),'role'=>'customer','is_active'=>1]);
  return redirect()->to('/login')->with('message','Akun dibuat. Silakan login.');
 }
 public function logout() { session()->destroy(); return redirect()->to('/login'); }
}
