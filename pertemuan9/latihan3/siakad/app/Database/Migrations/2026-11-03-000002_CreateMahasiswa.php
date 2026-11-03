<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Membuat tabel data mahasiswa. */
class CreateMahasiswa extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => ['type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE],
			'nim' => ['type' => 'VARCHAR', 'constraint' => 30],
			'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
			'program_studi' => ['type' => 'VARCHAR', 'constraint' => 100],
			'angkatan' => ['type' => 'SMALLINT', 'unsigned' => TRUE],
			'email' => ['type' => 'VARCHAR', 'constraint' => 254, 'null' => TRUE],
			'alamat' => ['type' => 'TEXT', 'null' => TRUE],
			'created_at' => ['type' => 'DATETIME', 'null' => TRUE],
			'updated_at' => ['type' => 'DATETIME', 'null' => TRUE],
		]);
		$this->forge->addKey('id', TRUE);
		$this->forge->addUniqueKey('nim');
		$this->forge->createTable('mahasiswa');
	}

	public function down()
	{
		$this->forge->dropTable('mahasiswa');
	}
}
