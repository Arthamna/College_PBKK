<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['name', 'email', 'password_hash', 'phone', 'role', 'is_active'];

    /** @var array<string, string|list<string>> */
    protected $validationRules = [
        'name'  => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'phone' => 'permit_empty|max_length[30]|regex_match[/^[0-9+\-\s]*$/]',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', mb_strtolower(trim($email)))->first();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findActive(int $id): ?array
    {
        return $this->where('id', $id)->where('is_active', 1)->first();
    }

    /**
     * Customers with their order counts, for the admin customer list.
     *
     * @return list<array<string, mixed>>
     */
    public function customersWithOrderCount(?string $search = null): array
    {
        $builder = $this->select('users.*, COUNT(orders.id) AS order_count')
            ->join('orders', 'orders.user_id = users.id', 'left')
            ->where('users.role', Role::Customer->value)
            ->groupBy('users.id')
            ->orderBy('users.created_at', 'DESC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        return $builder->findAll();
    }
}
