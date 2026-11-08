<?php
namespace App\Controllers;
use App\Models\ProductModel;
class ProductController extends ShopController {
 public function index() {
  $search=$this->request->getGet('q'); $search=is_string($search)?trim($search):'';
  $products=(new ProductModel())->where('status','active');
  if ($search!=='') { $products->groupStart()->like('name',$search)->orLike('description',$search)->groupEnd(); }
  return $this->page('catalog',['products'=>$products->orderBy('id','DESC')->findAll(),'search'=>$search]);
 }
 public function show(int $id) { $p=(new ProductModel())->where('status','active')->find($id); if (!$p) { $this->missing(); } return $this->page('product',['product'=>$p]); }
 public function image(int $id) {
  $p=(new ProductModel())->find($id); $u=$this->user();
  if (!$p || ($p['status']!=='active' && (!$u || $u['role']!=='administrator')) || !$p['image']) { $this->missing(); }
  $path=WRITEPATH.'uploads/products/'.basename($p['image']); if (!is_file($path)) { $this->missing(); }
  return $this->response->setHeader('Content-Type',mime_content_type($path))->setHeader('X-Content-Type-Options','nosniff')->setBody(file_get_contents($path));
 }
}
