<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $customer = $this->db->table('users')->where('email', 'customer@shopease.test')->get()->getRowArray();

        if ($customer === null || $this->db->table('addresses')->where('user_id', $customer['id'])->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');

        $this->db->table('addresses')->insert([
            'user_id'        => $customer['id'],
            'label'          => 'Rumah',
            'recipient_name' => 'Budi Customer',
            'phone'          => '081234567890',
            'street'         => 'Jl. Raya ITS No. 1, Keputih, Sukolilo',
            'city'           => 'Surabaya',
            'province'       => 'Jawa Timur',
            'postal_code'    => '60111',
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }
}
