<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DirectoryModel;
use App\Models\PhotoModel;
use CodeIgniter\HTTP\ResponseInterface;

class Photos extends BaseController
{
    protected $dirModel;
    protected $photoModel;

    public function __construct()
    {
        $this->dirModel = new DirectoryModel();
        $this->photoModel = new PhotoModel();
    }

    public function index()
    {
        //
    }

    public function uploadFile() {    
        $file = $this->request->getFile('file');
        $id = $this->request->getVar('id');
        $kode = $this->request->getVar('kode');

        $fileExtension = $file->getExtension();
        
        // Pastikan folder tujuan ada atau buat folder baru
        $uploadPath = 'uploads/' . $kode;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 777, true); // Buat folder jika belum ada
        }

        // Periksa apakah file valid dan belum dipindahkan
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Buat nama file baru yang unik
            $fileName = strtoupper(substr(md5(mt_rand()), 0, 3)) . '_' . $file->getClientName();
            
            // Pindahkan file ke folder tujuan
            $file->move($uploadPath, $fileName);
            
            // Jika file adalah video, buat thumbnail
            if (in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv'])) {
                $filePath = $uploadPath . '/' . $fileName;

                // // Path thumbnail yang akan dibuat
                $thumbnailPath = $filePath. '_thumb.jpg';

                // // Buat thumbnail menggunakan FFmpeg
                $command = "ffmpeg -i $filePath -ss 00:00:01.000 -vframes 1 $thumbnailPath";
                shell_exec($command); // Jalankan perintah FFmpeg untuk membuat thumbnail
            } 
            

            $data = [
                'file_name' => $fileName,
                'dir_id' => $id
            ];
            $this->photoModel->insert($data);
            // Tambahkan 1 ke kolom total_photo di tabel `directories`
            $this->photoModel->addTotalPhoto($id);
            // Return JSON response jika berhasil
            return $this->response->setJSON(['success' => true, 'message' => 'File berhasil diunggah.']);
        }

        // Return JSON response jika gagal
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengunggah file. Periksa log error untuk detail.']);
    }


    public function QRPhotos($kode) {
        $dir = $this->dirModel->getDetailDir($kode);
        $created_at = $dir->getFirstRow('array');
    
        // Menggunakan null coalescing operator
        $created_at_value = $created_at['dir_date'] ?? null;
        $WA_NUMBER = getenv('WA_NUMBER');
        $data = [
            'kode' => $kode,
            'photos' => $dir,
            'created_at' => $created_at_value,
            'WA_NUMBER' => $WA_NUMBER
        ];
    
        return view('customer/qr-photos', $data);
    }
    

    public function deletePhoto() {
        $id = $this->request->getVar('id');
        $kode = $this->request->getVar('kode');
        // Hapus foto dari database
        $this->photoModel->deletePhoto($id);        
    
        return redirect()->to('/dir/' . $kode)->with('message', 'Photo deleted successfully');

    }

    
}
