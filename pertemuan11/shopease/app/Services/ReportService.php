<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use CodeIgniter\Database\BaseConnection;

class ReportService
{
    public function __construct(private readonly BaseConnection $db)
    {
    }

    /**
     * @return array{products:int,customers:int,orders:int,transactions:int,sales:int,awaiting_verification:int}
     */
    public function dashboard(): array
    {
        $sales = $this->db->table('orders')
            ->selectSum('total', 'sales')
            ->whereIn('status', OrderStatus::saleValues())
            ->get()
            ->getRow('sales');

        return [
            'products'              => $this->db->table('products')->countAllResults(),
            'customers'             => $this->db->table('users')->where('role', Role::Customer->value)->countAllResults(),
            'orders'                => $this->db->table('orders')->countAllResults(),
            'transactions'          => $this->db->table('payments')->where('status', PaymentStatus::Verified->value)->countAllResults(),
            'sales'                 => (int) round((float) $sales),
            'awaiting_verification' => $this->db->table('payments')->where('status', PaymentStatus::WaitingVerification->value)->countAllResults(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function lowStock(int $threshold = 5): array
    {
        return $this->db->table('products')
            ->select('id, name, stock')
            ->where('status', 'active')
            ->where('stock <=', $threshold)
            ->orderBy('stock')
            ->limit(5)
            ->get()
            ->getResultArray();
    }

    /**
     * Sales recap for a period, grouped by transaction status.
     *
     * @return array{byStatus:list<array<string,mixed>>,orders:list<array<string,mixed>>,sales:int}
     */
    public function salesReport(string $from, string $to, ?OrderStatus $status): array
    {
        $start = $from . ' 00:00:00';
        $end   = $to . ' 23:59:59';

        $byStatus = $this->db->table('orders')
            ->select('status, COUNT(*) AS order_count, SUM(total) AS amount')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $query = $this->db->table('orders')
            ->select('orders.order_number, orders.status, orders.total, orders.created_at, users.name AS customer_name')
            ->join('users', 'users.id = orders.user_id')
            ->where('orders.created_at >=', $start)
            ->where('orders.created_at <=', $end)
            ->orderBy('orders.created_at', 'DESC');

        if ($status !== null) {
            $query->where('orders.status', $status->value);
        }

        $sales = 0;

        foreach ($byStatus as $row) {
            if (OrderStatus::from($row['status'])->isSale()) {
                $sales += (int) round((float) $row['amount']);
            }
        }

        return ['byStatus' => $byStatus, 'orders' => $query->get()->getResultArray(), 'sales' => $sales];
    }
}
