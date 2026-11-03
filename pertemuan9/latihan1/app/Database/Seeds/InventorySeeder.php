<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Idempotent: never overwrite existing accounts or inventory.
        foreach (['admin' => 'Admin123!', 'staff' => 'Staff123!'] as $role => $password) {
            if ($this->db->table('users')->where('username', $role)->countAllResults() === 0) {
                $this->db->table('users')->insert([
                    'username' => $role,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role'     => $role,
                ]);
            }
        }

        foreach (['Elektronik', 'Alat Tulis', 'Perlengkapan Kantor'] as $name) {
            if ($this->db->table('categories')->where('name', $name)->countAllResults() === 0) {
                $this->db->table('categories')->insert(['name' => $name]);
            }
        }

        if ($this->db->table('products')->countAllResults() === 0) {
            $categories = array_column($this->db->table('categories')->get()->getResultArray(), 'id', 'name');
            $this->db->table('products')->insertBatch([
                ['category_id' => $categories['Elektronik'], 'name' => 'Mouse Wireless', 'stock' => 24, 'price' => '125000.00', 'image' => null],
                ['category_id' => $categories['Elektronik'], 'name' => 'Keyboard USB', 'stock' => 12, 'price' => '185000.00', 'image' => null],
                ['category_id' => $categories['Alat Tulis'], 'name' => 'Buku Catatan A5', 'stock' => 48, 'price' => '25000.00', 'image' => null],
                ['category_id' => $categories['Perlengkapan Kantor'], 'name' => 'Stapler', 'stock' => 8, 'price' => '45000.00', 'image' => null],
            ]);
        }
    }
}
