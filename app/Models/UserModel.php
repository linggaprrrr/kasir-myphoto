<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $allowedFields = ['username', 'password', 'nama', 'photo'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


 
}
