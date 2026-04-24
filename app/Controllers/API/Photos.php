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


    public function createDir()
    {
        $data = $this->request->getPost();

        // ✅ Validate input first
        if (empty($data['kode_transaksi'])) {
            return $this->fail('kode_transaksi is required');
        }

        $dirPath = WRITEPATH . 'uploads/' . $data['kode_transaksi'];

        try {
            // ✅ Insert DB first
            $this->dirModel->insert([
                'kode_transaksi' => $data['kode_transaksi'],
                'cabang' => $data['cabang'] ?? '',
                'user' => $data['user'] ?? '',
            ]);

            // ✅ Safe directory creation
            if (!is_dir($dirPath)) {
                if (!mkdir($dirPath, 0775, true)) {
                    throw new \Exception('Failed to create directory: ' . $dirPath);
                }
            }

            return $this->respondCreated([
                'message' => 'success',
                'path' => $dirPath
            ]);

        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());

            return $this->failServerError($e->getMessage());
        }
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
