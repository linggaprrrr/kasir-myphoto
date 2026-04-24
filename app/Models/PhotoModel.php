<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoModel extends Model
{
    protected $table            = 'photos';
    protected $allowedFields = ['file_name', 'dir_id', 'file_url', 'status', 'created_at'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function addTotalPhoto($dirId)
    {
        
        $this->db->table('directories')
            ->where('id', $dirId)
            ->set('total_photo', 'total_photo + 1', false) 
            ->update();
    }

    public function deletePhoto($id)
    {
        
        $this->db->table('photos')->where('id', $id)->delete();

        
        $this->db->table('directories')
            ->set('total_photo', 'total_photo - 1', false) // false agar tidak di-escape
            ->update();
    }
}
