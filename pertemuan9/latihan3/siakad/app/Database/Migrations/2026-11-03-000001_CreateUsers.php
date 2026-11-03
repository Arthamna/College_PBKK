<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Membuat tabel akun administrator. */
class CreateUsers extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
			'username' => ['type' => 'VARCHAR', 'constraint' => 50],
			'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
			'created_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'updated_at' => ['type' => 'DATETIME', 'null' => TRUE],
		]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addUniqueKey('username');
		$this->forge->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
