<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TestClients extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name'        => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone_number'=> [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'created_at'  => [
                'type'      => 'TIMESTAMP',
                'null'      => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('test_clients');
    }

    public function down()
    {
        $this->forge->dropTable('test_clients');
    }
}
