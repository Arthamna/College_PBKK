<?php
namespace App\Services;
use DomainException;
class OrderService {
 public function __construct(private \CodeIgniter\Database\BaseConnection $db) {}
 public function change(int $id,int $actor,string $action,string $value=''): void {
  $this->db->transBegin();
  try {
   $o=$this->db->query('SELECT * FROM '.$this->db->prefixTable('orders').' WHERE id = ? FOR UPDATE',[$id])->getRowArray();
   $p=$this->db->table('payments')->where('order_id',$id)->get()->getRowArray();
   $u=$this->db->table('users')->where(['id'=>$actor,'is_active'=>1])->get()->getRowArray();
   if (!$o || !$p || !$u) { throw new DomainException('Pesanan tidak ditemukan.'); }
   $now=date('Y-m-d H:i:s'); $update=['updated_at'=>$now];
   if ($action==='submit') {
    if ($u['role']!=='customer' || $o['user_id']!=$actor || $o['order_status']!=='pending_payment' || !in_array($p['payment_status'],['unpaid','rejected'],true)) { throw new DomainException('Bukti tidak dapat diunggah pada status ini.'); }
    $status='waiting_verification'; $update+=['proof_file'=>$value,'payment_status'=>'submitted','rejection_reason'=>'','verified_by'=>null,'verified_at'=>null];
   } else {
    if ($u['role']!=='administrator') { throw new DomainException('Akses ditolak.'); }
    if ($action==='cancel') {
     if (!in_array($o['order_status'],['pending_payment','waiting_verification'],true) || $p['payment_status']==='verified') { throw new DomainException('Pesanan tidak dapat dibatalkan.'); }
     $items=$this->db->table('order_items')->where('order_id',$id)->orderBy('product_id')->get()->getResultArray();
     foreach ($items as $item) { $this->db->query('UPDATE '.$this->db->prefixTable('products').' SET stock = stock + ?, updated_at = ? WHERE id = ?',[$item['quantity'],$now,$item['product_id']]); }
     $status='cancelled';
    } else {
     if (!in_array($action,['verify','reject'],true) || $o['order_status']!=='waiting_verification' || $p['payment_status']!=='submitted') { throw new DomainException('Transisi status tidak valid.'); }
     if ($action==='reject' && trim($value)==='') { throw new DomainException('Alasan penolakan wajib diisi.'); }
     $status=$action==='verify'?'processing':'pending_payment';
     $update+=['payment_status'=>$action==='verify'?'verified':'rejected','rejection_reason'=>$action==='reject'?$value:'','verified_by'=>$actor,'verified_at'=>$now];
    }
   }
   $this->db->table('payments')->where('id',$p['id'])->update($update);
   $this->db->table('orders')->where('id',$id)->update(['order_status'=>$status,'changed_by'=>$actor,'updated_at'=>$now]);
   if (!$this->db->transStatus()) { throw new DomainException('Perubahan gagal disimpan.'); }
   $this->db->transCommit();
  } catch (\Throwable $e) { $this->db->transRollback(); throw $e; }
 }
}
