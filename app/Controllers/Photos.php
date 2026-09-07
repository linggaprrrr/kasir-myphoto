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
public function uploadFile()
{
    $file = $this->request->getFile('file');
    $id = $this->request->getVar('id');
    $kode = preg_replace('/[^A-Za-z0-9_-]/', '', $this->request->getVar('kode'));

    if (!$file || !$file->isValid() || $file->hasMoved()) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid upload'
        ]);
    }

    // Allowed mime types
    $allowedMime = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'video/mp4',
        'video/quicktime'
    ];

    $mime = $file->getMimeType();

    if (!in_array($mime, $allowedMime)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'File type not allowed'
        ]);
    }

    // Absolute path
    $uploadPath = FCPATH . 'uploads/' . $kode;

    // Create folder
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0775, true);
    }

    // Random filename
    $extension = $file->getExtension();
    $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

    // Move file
    $file->move($uploadPath, $fileName);

    // Video thumbnail
    if (str_starts_with($mime, 'video/')) {

        $filePath = $uploadPath . '/' . $fileName;
        $thumbnailPath = $filePath . '_thumb.jpg';

        $ffmpeg = sprintf(
            'ffmpeg -i %s -ss 00:00:01.000 -vframes 1 %s 2>&1',
            escapeshellarg($filePath),
            escapeshellarg($thumbnailPath)
        );

        shell_exec($ffmpeg);
    }

    // the file is already on disk here: without this the column default ('pending')
    // would hide it from the customer page forever
    $data = [
        'file_name' => $fileName,
        'dir_id' => $id,
        'status' => 'ready',
        'file_url' => base_url('uploads/' . $kode . '/' . $fileName)
    ];

    $this->photoModel->insert($data);
    $this->photoModel->addTotalPhoto($id);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Upload success'
    ]);
}


    public function QRPhotos($kode) {
        $rows = $this->dirModel->getDetailDir($kode)->getResultObject();

        // rows uploaded before the status column existed have none: treat those as ready
        $isPending = static fn($photo) => in_array($photo->status ?? '', ['pending', 'uploading'], true);

        $data = [
            'kode' => $kode,
            'photos' => array_values(array_filter($rows, static fn($p) => !$isPending($p))),
            'pending' => count(array_filter($rows, $isPending)),
            'total' => count($rows),
            'created_at' => $rows[0]->dir_date ?? null,
            'WA_NUMBER' => getenv('WA_NUMBER'),
        ];

        return view('customer/qr-photos', $data);
    }
    

    public function deletePhoto() {
        $id = $this->request->getVar('id');
        $kode = $this->request->getVar('kode');
        // Hapus foto dari database
        $this->photoModel->deletePhoto($id, $kode);        
    
        return redirect()->to('/dir/' . $kode)->with('message', 'Photo deleted successfully');

    }

    
}
