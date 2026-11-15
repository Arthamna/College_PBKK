<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table         = 'carts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id'];

    /**
     * Returns the id of the user's single active cart, creating it on first use.
     */
    public function idForUser(int $userId): int
    {
        $cart = $this->where('user_id', $userId)->first();

        if ($cart !== null) {
            return (int) $cart['id'];
        }

        return (int) $this->insert(['user_id' => $userId], true);
    }
}
