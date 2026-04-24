<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailToPhotosTable extends Migration
{
    public function up()
{
    // file_url
    if (!$this->db->fieldExists('file_url', 'photos')) {
            $this->forge->addColumn('photos', [
                'file_url' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }

        // status
        if (!$this->db->fieldExists('status', 'photos')) {
            $this->forge->addColumn('photos', [
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'pending',
                ],
            ]);

            // Optional: add CHECK constraint
            $this->db->query("
                ALTER TABLE photos
                ADD CONSTRAINT check_status
                CHECK (status IN ('pending','uploading','ready','failed'))
            ");
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('file_url', 'photos')) {
            $this->forge->dropColumn('photos', 'file_url');
        }

        if ($this->db->fieldExists('status', 'photos')) {
            // Drop constraint first (PostgreSQL)
            $this->db->query("ALTER TABLE photos DROP CONSTRAINT IF EXISTS check_status");

            $this->forge->dropColumn('photos', 'status');
        }
    }
}
