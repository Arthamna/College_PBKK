<?php
namespace App\Models;
class PaymentModel extends \CodeIgniter\Model {
 protected $table='payments';
 protected $allowedFields=['order_id', 'method', 'amount', 'proof_file', 'payment_status', 'rejection_reason', 'verified_by', 'verified_at'];
 protected $useTimestamps=true;
}
