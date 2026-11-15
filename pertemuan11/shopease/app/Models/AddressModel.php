<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table         = 'addresses';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['user_id', 'label', 'recipient_name', 'phone', 'street', 'city', 'province', 'postal_code'];

    /** @var array<string, string> */
    protected $validationRules = [
        'label'          => 'required|max_length[50]',
        'recipient_name' => 'required|min_length[2]|max_length[100]',
        'phone'          => 'required|max_length[30]|regex_match[/^[0-9+\-\s]+$/]',
        'street'         => 'required|min_length[5]|max_length[500]',
        'city'           => 'required|max_length[100]',
        'province'       => 'required|max_length[100]',
        'postal_code'    => 'required|exact_length[5]|numeric',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function forUser(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('id')->findAll();
    }

    /**
     * Only returns the address when it belongs to the user (prevents IDOR).
     *
     * @return array<string, mixed>|null
     */
    public function findOwned(int $addressId, int $userId): ?array
    {
        return $this->where('id', $addressId)->where('user_id', $userId)->first();
    }
}
