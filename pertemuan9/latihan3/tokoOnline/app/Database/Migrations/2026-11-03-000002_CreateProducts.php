<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Prices are stored as whole rupiah, without floating point arithmetic. */
class CreateProducts extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'name' => ['type' => 'VARCHAR', 'constraint' => 150],
			'description' => ['type' => 'TEXT', 'null' => TRUE],
			'price' => ['type' => 'INT', 'unsigned' => TRUE],
			'stock' => ['type' => 'INT', 'unsigned' => TRUE, 'default' => 0],
			'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
			'created_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'updated_at' => ['type' => 'DATETIME', 'null' => TRUE],
		]);
		$this->forge->addKey('id', TRUE);
		$this->forge->createTable('products');
	}

	public function down()
	{
		$this->forge->dropTable('products');
	}
}
