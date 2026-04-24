<?= $this->extend('components/layout') ?>

<?= $this->section('content') ?>
<!-- Tambahkan CSS untuk border dashed -->
<style>
    .dropzone {
        border: 2px dashed #5A5A5A; /* Garis putus-putus dengan warna abu-abu */
        padding: 20px;
        border-radius: 10px; /* Agar sudutnya sedikit melengkung */
        text-align: center;
        background-color: #f9f9f9; /* Tambahkan warna latar belakang ringan */
    }

    /* Anda bisa menambahkan lebih banyak styling sesuai kebutuhan */
</style>


<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet" />
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">[ <code><?= $kode ?> - <?= strtoupper($cabang) ?></code> ]</h3>
            <div class="nk-block-des text-soft">
                <p>Upload Photo <?= $created_at ?></p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <div class="nk-block-head-content">
                    <a href="/dirs" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-chevron-left"></em><span>Back</span></a>                    
                </div>
            </div>
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
<form action="/upload/uploadFile" class="dropzone" id="myDropzone2"  >
    <input type="hidden" name="id" value="<?= $dir_id ?>" id="dir_id">
    <input type="hidden" name="kode" value="<?= $kode ?>" id="kode">
    <!--begin::Input group-->
    <div class="fv-row">
        <!--begin::Dropzone-->
        <div id="kt_dropzonejs_example_1">
            <!--begin::Message-->
            <div class="dz-message needsclick">
                <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>

                <!--begin::Info-->
                <div class="ms-4">
                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop photos here or click to upload.</h3>
                    <span class="fs-7 fw-semibold text-gray-500">Upload up to 1000 photos</span>
                </div>
                <!--end::Info-->
            </div>
        </div>
        <!--end::Dropzone-->
    </div>
    
</form>

</div><!-- .nk-block -->
<div class="nk-block">
    <p class="strong text-danger font-weight-bold">* Silakan refresh halaman setelah mengunggah foto.</p>
    <hr>
    <div class="row g-gs">
        <?php if ($created_at && (strtotime($created_at) < strtotime('-5 days'))) : ?>
            <div class="alert alert-danger alert-icon">
                <em class="icon ni ni-alert-circle"></em>This QR code has expired.
            </div>
        <?php endif; ?>

        <?php if ($photos->getNumRows() > 0) : ?>
            <div class="page-title">
                <label for="">Total Photo: <?= $photos->getNumRows() ?></label>
            </div>
            <?php foreach ($photos->getResultObject() as $photo) : ?>
                <div class="col-sm-6 col-lg-2">
                    <div class="gallery card">
                        <?php 
                        $filename = $photo->file_name;
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Ambil ekstensi file dan ubah menjadi lowercase
                        
                        // Daftar ekstensi video yang ingin diperiksa
                        $videoExtensions = ['mp4', 'mkv', 'mov', 'avi'];
                        
                        // Cek apakah ekstensi file ada di dalam array ekstensi video
                        if (in_array($extension, $videoExtensions)) {
                            ?>
                                <a class="gallery-image popup-image" href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name.'_thumb.jpg') ?>">
                                    <img class="w-100 lazyload rounded-top" data-src="<?=base_url('/uploads'.'/'.$kode.'/'. $photo->file_name.'_thumb.jpg') ?>" alt="">
                                </a>
                            <?php
                        } else {
                            ?>
                            <a class="gallery-image popup-image" href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>">
                                <img class="w-100 lazyload rounded-top" data-src="<?=base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>" alt="">
                            </a>
                            <?php
                        }
                        
                        ?>
                        
                        <div class="gallery-body align-center justify-between flex-wrap g-2 p-2">
                            <div class="user-card">                            
                                <div class="user-info">                     
                                    <span class="lead-text">
                                        <?= (strlen($photo->file_name) > 18) ? substr($photo->file_name, 0, 18) . '...' : $photo->file_name ?>
                                    </span>
                                    <span class="sub-text"><?= date('d/m/Y H:i A', strtotime($photo->created_at)) ?></span>
                                </div>
                            </div>
                            <div class="justify-content-end">
                                <a href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>" class="btn btn-link p-0" download="<?= $photo->file_name ?>">
                                    <em class="icon ni ni-download"></em>                                    
                                </a>
                                <form action="/delete-photo" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                    <input type="hidden" name="id" value="<?= $photo->photo_id ?>">
                                    <input type="hidden" name="kode" value="<?= $kode ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0" style="border: none; background: none;">
                                        <em class="icon ni ni-trash-alt"></em>
                                    </button>
                                </form>
                            </div>
                        </div>                 
                    </div>
                    
                </div>            
            <?php endforeach ?>
        <?php else : ?>           
            <div class="alert alert-warning alert-icon">
                <em class="icon ni ni-alert-circle"></em> No photos available. Please upload your photos to proceed.
            </div>
 
        <?php endif ?>
    </div>
</div>
        

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/qrcode/lazysizes.min.js"></script>
<script>
        // Inisialisasi Dropzone tanpa limitasi
        Dropzone.autoDiscover = false; // Mencegah Dropzone auto-attach

        var myDropzone = new Dropzone("#myDropzone2", {
            maxFiles: 1000, // Set jumlah maksimal file yang bisa diupload
            maxFilesize: null, // Tidak ada batasan ukuran file
            url: "/upload/uploadFile", // URL tujuan
            init: function() {
                console.log("Dropzone initialized");
            }
        });
    </script>

<?= $this->endSection() ?>