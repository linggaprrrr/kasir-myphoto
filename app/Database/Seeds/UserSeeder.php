<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Admin 1',
                'username'    => 'adminmedia',
                'password' => password_hash('1234', PASSWORD_BCRYPT),
            ],
            
        ];
         // Insert data ke dalam tabel users
         $this->db->table('users')->insertBatch($data);

    }
}
