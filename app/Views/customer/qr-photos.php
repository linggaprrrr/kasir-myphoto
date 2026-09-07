<?= $this->extend('customer/components/layout') ?>

<?= $this->section('content') ?>
<style>
 .float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}

.my-float{
	margin-top:16px;
}
</style>
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">MyPhoto [<?= $kode ?>]</h3>
            <div class="nk-block-des text-soft">
                <p></p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <div class="nk-block-head-content">
                    
                </div>
            </div>
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->

<div class="nk-block">
        
    <div class="row g-gs">
        <?php if ($pending > 0) : ?>
            <div class="alert alert-info alert-icon">
                <em class="icon ni ni-upload-cloud"></em>
                <?= $pending ?> of <?= $total ?> photos are still uploading. This page refreshes by itself &mdash; no need to rescan.
            </div>
        <?php endif ?>

        <?php if (count($photos) > 0) : ?>
            <div class="page-title">
                <label for="">Total: <?= count($photos) ?><?= $pending > 0 ? ' / ' . $total : '' ?></label>
            </div>
            

                <?php if ($created_at && (strtotime($created_at) < strtotime('-5 days'))) : ?>
                    <div class="alert alert-danger alert-icon">
                        <em class="icon ni ni-alert-circle"></em>Your QR code has expired. Please contact us for further assistance.
                    </div>
                <?php else : ?>
                    <?php foreach ($photos as $photo) : ?>
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
                                        <div class="gallery-body align-center justify-between flex-wrap g-2 p-2">
                                            <div class="user-card">                            
                                                <div class="user-info">                     
                                                    <span class="lead-text">
                                                        
                                                    </span>
                                                    <span class="sub-text"><?= date('d/m/Y H:i A', strtotime($photo->created_at)) ?></span>
                                                </div>
                                            </div>
                                            <div class="justify-content-end">
                                                <a href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>" class="btn btn-primary" download="<?= $photo->file_name ?>">                                    
                                                    <em class="icon ni ni-video-fill me-1"></em>
                                                    Download
                                                </a>
                                                
                                            </div>
                                        </div>                                  
                                        <?php
                                    } else {
                                        ?>
                                        
                                        <a class="gallery-image popup-image" href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>">
                                            <img class="w-100 lazyload rounded-top" data-src="<?=base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>" alt="">
                                        </a>
                                        <div class="gallery-body align-center justify-between flex-wrap g-2 p-2">
                                            <div class="user-card">                            
                                                <div class="user-info">                     
                                                    <span class="lead-text">
                                                        
                                                    </span>
                                                    <span class="sub-text"><?= date('d/m/Y H:i A', strtotime($photo->created_at)) ?></span>
                                                </div>
                                            </div>
                                            <div class="justify-content-end">
                                                <a href="<?= base_url('/uploads'.'/'.$kode.'/'. $photo->file_name) ?>" class="btn btn-primary" download="<?= $photo->file_name ?>">                                    
                                                    <em class="icon ni ni-img-fill me-1"></em>
                                                    Download
                                                </a>
                                                
                                            </div>
                                        </div>   
                                        <?php
                                    }
                                    
                                ?>                        
                                            
                            </div>
                            
                        </div>            
                    <?php endforeach ?>
                <?php endif; ?>
        <?php elseif ($pending === 0) : ?>           
            <div class="alert alert-warning alert-icon">
                <em class="icon ni ni-alert-circle"></em> No photos available. Please upload your photos to proceed.
            </div>
 
        <?php endif ?>
    </div>
</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<a href="https://wa.me/<?= $WA_NUMBER ?>" class="float" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
</a>

        

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="/qrcode/lazysizes.min.js"></script>
<?php if ($pending > 0) : ?>
<script>
    // ponytail: plain reload while uploads are outstanding; swap for a status endpoint
    // if the reload ever interrupts a download
    setTimeout(() => location.reload(), 8000);
</script>
<?php endif ?>
<?= $this->endSection() ?>