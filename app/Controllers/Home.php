<?php

namespace App\Controllers;

use App\Models\DirectoryModel;
use App\Models\PhotoModel;

class Home extends BaseController
{
    protected $dirModel;
    protected $photoModel;


    public function __construct()
    {
        $this->dirModel = new DirectoryModel();
        $this->photoModel = new PhotoModel();
        
    
    }

    public function dashboard() {
        
        return view('dashboard');
    }
    
    public function dirs() {
       

        return view('dirs');
    }

    
    public function uploadPhotos($kode) {
        $dir = $this->dirModel->getDetailDir($kode);
        $getDirID = $this->dirModel
            ->select('id, cabang, created_at')
            ->where('kode_transaksi', $kode)
            ->get()->getFirstRow('array');
        if (is_null($getDirID)) {
            $dirID = null;
            $cabang = null;
            $date = null;
        } else {
            $dirID = $getDirID['id'];
            $cabang = $getDirID['cabang'];
            $date = $getDirID['created_at'];
        }

        $data = [
            'kode' => $kode,
            'dir_id' => $dirID,
            'cabang' => $cabang,
            'photos' => $dir,
            'created_at' => $date
        ];
        
        return view('upload_photos', $data);
    }

    

    
}
