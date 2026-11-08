<?php
namespace App\Controllers\Admin;
use App\Models\CategoryModel;
use App\Models\ProductModel;
class CatalogController extends \App\Controllers\ShopController {
 private function model(string $kind) { if (!in_array($kind,['categories','products'],true)) { $this->missing(); } return $kind==='categories'?new CategoryModel():new ProductModel(); }
 public function index(string $kind) { return $this->page('admin_catalog',['kind'=>$kind,'rows'=>$this->model($kind)->findAll()]); }
 public function edit(string $kind,int $id=0) {
  $row=$id?$this->model($kind)->find($id):[]; if ($id && !$row) { $this->missing(); }
  return $this->page('admin_edit',['kind'=>$kind,'row'=>$row,'categories'=>(new CategoryModel())->findAll()]);
 }
 public function save(string $kind,int $id=0) {
  $m=$this->model($kind); $old=$id?$m->find($id):[]; if ($id && !$old) { $this->missing(); }
  $data=[]; foreach (['name','slug','description'] as $key) { $data[$key]=trim((string)$this->request->getPost($key)); }
  $rules=['name'=>'required|max_length[255]','slug'=>'required|max_length[255]|regex_match[/^[a-z0-9]+(?:-[a-z0-9]+)*$/]','description'=>'permit_empty|max_length[10000]'];
  if ($kind==='products') {
   foreach (['category_id','price','stock','status'] as $key) { $data[$key]=(string)$this->request->getPost($key); }
   $rules+=['category_id'=>'required|is_natural_no_zero|is_not_unique[categories.id]','price'=>'required|regex_match[/^\d{1,10}(\.\d{1,2})?$/]','stock'=>'required|regex_match[/^\d{1,9}$/]','status'=>'required|in_list[active,inactive]'];
   $data['image']=$old['image']??'';
  } else { $data['is_active']=$this->request->getPost('is_active')==='1'?1:0; }
  $url='/admin/'.$kind.($id?'/'.$id.'/edit':'/new');
  if (!$this->validateData($data,$rules)) { return $this->fail(implode(' ',$this->validator->getErrors()),$url); }
  $duplicate=$m->where('slug',$data['slug'])->first(); if ($duplicate && $duplicate['id']!=$id) { return $this->fail('Slug sudah digunakan.',$url); }
  if ($kind==='products' && $this->request->getFile('image') && $this->request->getFile('image')->getError()!==UPLOAD_ERR_NO_FILE) {
   try { $data['image']=$this->upload('image','products'); } catch (\DomainException $e) { return $this->fail($e->getMessage(),$url); }
  }
  $id?$m->update($id,$data):$m->insert($data); return redirect()->to('/admin/'.$kind)->with('message','Data disimpan.');
 }
 public function remove(string $kind,int $id) {
  $m=$this->model($kind); if (!$m->find($id)) { $this->missing(); }
  if ($kind==='categories') {
   if ((new ProductModel())->where('category_id',$id)->countAllResults()) { return $this->fail('Kategori masih memiliki produk. Nonaktifkan atau pindahkan produk terlebih dahulu.','/admin/categories'); } $m->delete($id);
  } else { $m->update($id,['status'=>'inactive']); }
  return redirect()->to('/admin/'.$kind);
 }
}
