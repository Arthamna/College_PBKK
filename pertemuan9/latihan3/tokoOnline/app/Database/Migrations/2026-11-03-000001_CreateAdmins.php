<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Creates the accounts used exclusively by the administration area. */
class CreateAdmins extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'name' => ['type' => 'VARCHAR', 'constraint' => 100],
			'email' => ['type' => 'VARCHAR', 'constraint' => 150],
			'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
			'created_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'updated_at' => ['type' => 'DATETIME', 'null' => TRUE],
		]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addUniqueKey('email');
		$this->forge->createTable('admins');
	}

	public function down()
	{
		$this->forge->dropTable('admins');
	}
}
