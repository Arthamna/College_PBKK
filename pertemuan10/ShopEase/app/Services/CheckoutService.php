<?php
namespace App\Services;
use DomainException;
class CheckoutService {
 public function __construct(private \CodeIgniter\Database\BaseConnection $db) {}
 public function checkout(int $userId,int $addressId,string $token): int {
  if (!preg_match('/^[a-f0-9]{64}$/D',$token)) { throw new DomainException('Token checkout tidak valid.'); }
  $this->db->transBegin();
  try {
   $cart=$this->db->query('SELECT * FROM '.$this->db->prefixTable('carts').' WHERE user_id = ? FOR UPDATE',[$userId])->getRowArray();
   $existing=$this->db->table('orders')->where(['user_id'=>$userId,'checkout_token'=>$token])->get()->getRowArray();
   if ($existing) { $this->db->transCommit(); return (int)$existing['id']; }
   $address=$this->db->table('addresses')->where(['id'=>$addressId,'user_id'=>$userId])->get()->getRowArray();
   if (!$address) { throw new DomainException('Alamat tidak valid.'); }
   $items=$cart?$this->db->table('cart_items')->where('cart_id',$cart['id'])->orderBy('product_id')->get()->getResultArray():[];
   if (!$items) { throw new DomainException('Keranjang kosong.'); }
   $subtotal='0.00'; $lines=[];
   foreach ($items as $item) {
    $p=$this->db->query('SELECT * FROM '.$this->db->prefixTable('products').' WHERE id = ? FOR UPDATE',[$item['product_id']])->getRowArray();
    if (!$p || $p['status']!=='active' || $item['quantity']<1 || $p['stock']<$item['quantity']) { throw new DomainException('Produk nonaktif atau stok tidak cukup.'); }
    $line=bcmul($p['price'],(string)$item['quantity'],2); $subtotal=bcadd($subtotal,$line,2);
    $lines[]=['product_id'=>$p['id'],'product_name'=>$p['name'],'price'=>$p['price'],'quantity'=>$item['quantity'],'subtotal'=>$line];
   }
   $shipping=(string)env('shop.shippingCost','10000.00');
   if (!preg_match('/^\d{1,10}(\.\d{1,2})?$/D',$shipping)) { throw new DomainException('Konfigurasi ongkir tidak valid.'); }
   $now=date('Y-m-d H:i:s'); $total=bcadd($subtotal,$shipping,2);
   $this->db->table('orders')->insert(['user_id'=>$userId,'address_id'=>$addressId,'checkout_token'=>$token,'order_number'=>'SE-'.strtoupper(bin2hex(random_bytes(12))),'address_snapshot'=>json_encode($address,JSON_UNESCAPED_UNICODE),'subtotal'=>$subtotal,'shipping_cost'=>$shipping,'total'=>$total,'order_status'=>'pending_payment','notes'=>'','changed_by'=>$userId,'created_at'=>$now,'updated_at'=>$now]);
   $id=(int)$this->db->insertID();
   foreach ($lines as $line) {
    $this->db->table('order_items')->insert($line+['order_id'=>$id,'created_at'=>$now,'updated_at'=>$now]);
    $this->db->query('UPDATE '.$this->db->prefixTable('products').' SET stock = stock - ?, updated_at = ? WHERE id = ?',[$line['quantity'],$now,$line['product_id']]);
   }
   $this->db->table('payments')->insert(['order_id'=>$id,'method'=>'manual_transfer','amount'=>$total,'proof_file'=>'','payment_status'=>'unpaid','rejection_reason'=>'','created_at'=>$now,'updated_at'=>$now]);
   $this->db->table('cart_items')->where('cart_id',$cart['id'])->delete();
   if (!$this->db->transStatus()) { throw new DomainException('Pesanan gagal disimpan.'); }
   $this->db->transCommit(); return $id;
  } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
 }
}
