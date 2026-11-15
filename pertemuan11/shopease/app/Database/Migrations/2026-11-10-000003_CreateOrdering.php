<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdering extends Migration
{
    private const array ATTRIBUTES = ['ENGINE' => 'InnoDB'];

    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_number'     => ['type' => 'VARCHAR', 'constraint' => 30],
            'user_id'          => ['type' => 'INT', 'unsigned' => true],
            'subtotal'         => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
            'shipping_cost'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
            'total'            => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => '0.00'],
            'status'           => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending_payment'],
            'address_snapshot' => ['type' => 'TEXT'],
            'notes'            => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_number');
        $this->forge->addKey(['user_id', 'created_at']);
        $this->forge->addKey(['status', 'created_at']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('orders', true, self::ATTRIBUTES);

        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_id'     => ['type' => 'INT', 'unsigned' => true],
            'product_id'   => ['type' => 'INT', 'unsigned' => true],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'unit_price'   => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'quantity'     => ['type' => 'INT', 'unsigned' => true],
            'subtotal'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('order_items', true, self::ATTRIBUTES);

        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_id'         => ['type' => 'INT', 'unsigned' => true],
            'amount'           => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'method'           => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'bank_transfer'],
            'proof_file'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'           => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unpaid'],
            'rejection_reason' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'verified_by'      => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'verified_at'      => ['type' => 'DATETIME', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_id');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('payments', true, self::ATTRIBUTES);

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'order_id'        => ['type' => 'INT', 'unsigned' => true],
            'courier'         => ['type' => 'VARCHAR', 'constraint' => 50],
            'tracking_number' => ['type' => 'VARCHAR', 'constraint' => 60],
            'status'          => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'shipped'],
            'shipped_at'      => ['type' => 'DATETIME', 'null' => true],
            'delivered_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('shipments', true, self::ATTRIBUTES);
    }

    public function down(): void
    {
        $this->forge->dropTable('shipments', true);
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('order_items', true);
        $this->forge->dropTable('orders', true);
    }
}
