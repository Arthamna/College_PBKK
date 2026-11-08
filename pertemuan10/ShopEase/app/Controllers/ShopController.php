<?php
namespace App\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
abstract class ShopController extends BaseController {
 protected $helpers=['url','form'];
 protected function user(): ?array { return session('user_id')?(new \App\Models\UserModel())->find(session('user_id')):null; }
 protected function page(string $view,array $data=[]): string { return view('shop/'.$view,$data+['user'=>$this->user()]); }
 protected function missing(): never { throw PageNotFoundException::forPageNotFound(); }
 protected function fail(string $message,string $url) { return redirect()->to($url)->with('error',$message)->withInput(); }
 protected function upload(string $field,string $folder): string {
  if (!$this->validateData([],[$field=>"uploaded[$field]|max_size[$field,2048]|ext_in[$field,jpg,jpeg,png]|mime_in[$field,image/jpeg,image/png]|is_image[$field]"])) { throw new \DomainException('File harus JPEG/PNG maksimal 2 MB.'); }
  $file=$this->request->getFile($field); $name=$file->getRandomName(); $file->move(WRITEPATH.'uploads/'.$folder,$name); return $name;
 }
}
