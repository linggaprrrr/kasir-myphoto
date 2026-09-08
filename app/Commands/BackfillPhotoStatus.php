<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Foto yang diunggah admin sebelum commit baa3236 masuk tanpa kolom status,
 * jadi terkunci di default 'pending' dan tidak pernah tampil di halaman QR.
 * Perintah ini melepasnya — hanya untuk baris yang filenya benar-benar ada di disk,
 * supaya baris pending milik penjualan file (yang filenya memang belum dikirim)
 * tidak ikut ditandai ready.
 */
class BackfillPhotoStatus extends BaseCommand
{
    protected $group       = 'Photos';
    protected $name        = 'photos:backfill-status';
    protected $description = 'Tandai ready foto pending yang filenya sudah ada di disk. Default dry-run, pakai --apply untuk menulis.';
    protected $usage       = 'photos:backfill-status [--apply] [--path /jalur/ke/uploads]';
    protected $options     = [
        '--apply' => 'Tulis perubahan. Tanpa ini hanya dry-run.',
        '--path'  => 'Folder uploads, kalau layout server beda dari FCPATH.',
    ];

    public function run(array $params)
    {
        $apply = (bool) CLI::getOption('apply');
        $base  = rtrim((string) (CLI::getOption('path') ?: FCPATH . 'uploads'), '/');

        // layout server bisa beda dari FCPATH; tanpa cek ini semua file terbaca
        // "tidak ada" dan perintahnya diam-diam tidak melakukan apa pun
        if (!is_dir($base)) {
            CLI::error('Folder uploads tidak ditemukan: ' . $base);
            CLI::write('Tunjukkan jalur yang benar dengan --path /jalur/ke/uploads');

            return EXIT_ERROR;
        }

        CLI::write('Folder uploads: ' . $base);
        $db = Database::connect();

        $rows = $db->table('photos')
            ->select('photos.id, photos.file_name, directories.kode_transaksi')
            ->join('directories', 'directories.id = photos.dir_id')
            ->whereIn('photos.status', ['pending', 'uploading'])
            ->get()->getResultArray();

        $found = 0;
        $missing = 0;

        foreach ($rows as $row) {
            $path = $base . '/' . $row['kode_transaksi'] . '/' . $row['file_name'];

            if (!is_file($path)) {
                $missing++;
                continue;
            }

            $found++;
            CLI::write('  ready: ' . $row['kode_transaksi'] . '/' . $row['file_name']);

            if ($apply) {
                $db->table('photos')->where('id', $row['id'])->update([
                    'status'   => 'ready',
                    'file_url' => base_url('uploads/' . $row['kode_transaksi'] . '/' . $row['file_name']),
                ]);
            }
        }

        CLI::newLine();
        CLI::write('Pending diperiksa : ' . count($rows));
        CLI::write('Ada di disk       : ' . $found, 'green');
        CLI::write('Belum ada filenya  : ' . $missing . ' (dibiarkan pending)', 'yellow');
        CLI::write($apply ? 'Perubahan DITULIS.' : 'Dry-run. Jalankan ulang dengan --apply untuk menulis.', $apply ? 'green' : 'yellow');
    }
}
