<?php
namespace App\Database\Seeds;
class ShopSeeder extends \CodeIgniter\Database\Seeder {
 public function run() {
  $password=env('shop.seedPassword');
  if (!$password || strlen($password)<8) { throw new \RuntimeException('Set shop.seedPassword minimal 8 karakter di .env.'); }
  foreach (['administrator','operator','customer'] as $role) {
   $m=new \App\Models\UserModel(); $email=$role.'@example.test';
   if (!$m->where('email',$email)->first()) { $m->insert(['name'=>ucfirst($role),'email'=>$email,'role'=>$role,'password_hash'=>password_hash($password,PASSWORD_DEFAULT),'is_active'=>1]); }
  }
  $m=new \App\Models\CategoryModel(); if (!$m->where('slug','contoh')->first()) { $m->insert(['name'=>'Contoh','slug'=>'contoh','description'=>'Produk demonstrasi','is_active'=>1]); }
  $cat=$m->where('slug','contoh')->first(); $m=new \App\Models\ProductModel();
  foreach (['Buku','Tas','Pulpen'] as $i=>$name) { if (!$m->where('slug',strtolower($name))->first()) { $m->insert(['category_id'=>$cat['id'],'name'=>$name,'slug'=>strtolower($name),'description'=>'Produk contoh ShopEase','price'=>($i+1)*10000,'stock'=>10,'image'=>'','status'=>'active']); } }
 }
}
