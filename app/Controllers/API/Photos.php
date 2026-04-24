<?php

namespace App\Controllers\API;

use App\Models\DirectoryModel;
use App\Models\PhotoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Photos extends ResourceController
{
    protected $dirModel;
    protected $photoModel;
    protected $format    = 'json';

    public function __construct()
    {
        $this->dirModel = new DirectoryModel();
        $this->photoModel = new PhotoModel();
    }


    public function createDir() {
        $data = $this->request->getPost();
        $dir = [
            'kode_transaksi' => $data['kode_transaksi'],
            'cabang' => $data['cabang'],
            'user' => $data['user']
        ];

        $dirPath = 'uploads/' . $data['kode_transaksi'];

        if ($this->dirModel->insert($dir)) {
            if (!is_dir($dirPath)) {                
                mkdir($dirPath, 0777, true);
            } else {
                // Jika direktori sudah ada, kamu bisa tambahkan logika atau return jika dibutuhkan
                return $this->respond(['message' => 'Directory already exists'], 400); // 400 untuk Bad Request
            }
            
            
            return $this->respondCreated(['message' => 'success']);
        }

        return $this->failValidationError('Failed to create photo');
    }

    

    public function validateDirectoryExpiry() {
        // Calculate the date 5 days ago
        $fiveDaysAgo = date('Y-m-d', strtotime('-5 days'));
        $getDir = $this->dirModel->where('created_at <', $fiveDaysAgo)->get();
        
        foreach($getDir->getResultObject() as $dir) {  
            $folder = '/uploads/'. $dir->kode_transaksi; 
            if (is_dir($folder)) {
                $this->deleteDirectory($folder);
            }
            echo $folder. "<br>";
        }
    }

    public function deleteDirectory($dir) {
        $kode = $dir;
        $dir = 'uploads/'. $dir; 
        if (!is_dir($dir)) {
            return $this->failNotFound('Directory not found');
        }
    
        // Fungsi untuk menghapus isi direktori secara rekursif
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') {
                continue; // Lewati referensi direktori saat ini dan induk
            }
    
            $path = $dir . DIRECTORY_SEPARATOR . $item;
    
            if (is_dir($path)) {
                // Jika adalah direktori, hapus secara rekursif
                $this->deleteDirectory($path);
            } else {
                // Jika adalah file, hapus file
                unlink($path);
            }
        }
    
        // Hapus direktori setelah semua isi dihapus
        rmdir($dir);
    
        return $this->respondCreated(['message' => 'success']);
    }

    public function uploadPhoto()
    {
        $kode = $this->request->getPost('kode_transaksi');
        $fileName = $this->request->getPost('file_name');
        $file = $this->request->getFile('file');

        
        if (!$kode || !$fileName || !$file) {
            return $this->failValidationError('Invalid request');
        }

        $db = \Config\Database::connect();

        // 🔍 find directory
        $dir = $this->dirModel
            ->where('kode_transaksi', $kode)
            ->get()
            ->getRowArray();

        if (!$dir) {
            return $this->failNotFound('Directory not found');
        }

        $db->transStart();

        // 🔒 LOCK photo row
        $photo = $db->query("
            SELECT * FROM photos
            WHERE dir_id = ? AND file_name = ?
            FOR UPDATE
        ", [$dir['id'], $fileName])->getRowArray();

        // ❌ if photo not registered
        if (!$photo) {
            $db->transRollback();
            return $this->failNotFound('Photo not registered in transaction');
        }

        // ✅ already uploaded (idempotent)
        if ($photo['status'] === 'ready') {
            $db->transCommit();
            return $this->respond([
                'status' => 'success',
                'message' => 'Already uploaded',
                'url' => $photo['file_url']
            ]);
        }

        // 🔄 mark uploading
        $this->photoModel->update($photo['id'], [
            'status' => 'uploading'
        ]);

        $db->transCommit();

        // 📦 SAVE FILE (outside transaction)
        $newName = $fileName;
        $dirPath = FCPATH . 'uploads/' . $kode;

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $file->move($dirPath, $newName);

        $url = base_url('uploads/' . $kode . '/' . $newName);

        // ✅ final update
        $this->photoModel->update($photo['id'], [
            'status' => 'ready',
            'file_url' => $url
        ]);

        return $this->respond([
            'status' => 'success',
            'url' => $url
        ]);
    }

    public function registerPhotos()
    {
        $kode = $this->request->getPost('kode_transaksi');
        $files = $this->request->getPost('files'); 

        $dir = $this->dirModel
            ->where('kode_transaksi', $kode)
            ->get()->getFirstRow('array');
        
        if (!$dir) {
            return $this->failNotFound('Directory not found');
        }

        foreach ($files as $fileName) {
            $this->photoModel->insert([
                'dir_id' => $dir['id'],
                'file_name' => $fileName,
                'status' => 'pending'
            ]);
        }

        return $this->respond(['status' => 'success']);

    }




    
}
