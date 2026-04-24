<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectoryModel extends Model
{
    protected $table            = 'directories';
    protected $allowedFields = ['kode_transaksi', 'cabang', 'user', 'created_at', 'status'];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getDetailDir($kode) {
        $query = $this->db->table('directories')
            ->select('directories.kode_transaksi, directories.created_at as dir_date, photos.id as photo_id, photos.file_name, photos.created_at')
            ->join('photos', 'photos.dir_id = directories.id')            
            ->where('directories.kode_transaksi', $kode)
            ->orderBy('photos.id', 'DESC')
            ->get();

        return $query;
        
    }

    public function totalCount($search = null) {
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

        $builder = $this->db->table('directories')
                            ->where('created_at >=', $sevenDaysAgo);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('kode_transaksi', $search)
                    ->orLike('cabang', $search)
                    ->orLike('user', $search)
                    ->groupEnd();
        }

        return $builder->countAllResults(false); // langsung return jumlah
    }



    
    
    public function totalCountData($search = null, $start = null, $length = null) {
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

        $builder = $this->db->table('directories')
                            ->where('created_at >=', $sevenDaysAgo);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('kode_transaksi', $search)
                    ->orLike('cabang', $search)
                    ->orLike('user', $search)
                    ->groupEnd();
        }

        $builder->orderBy('id', 'DESC');

        if (!is_null($length) && !is_null($start)) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }


    
    
    
 }
