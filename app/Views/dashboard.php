<?= $this->extend('components/layout') ?>

<?= $this->section('content') ?>
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">MyPhoto Dashboard</h3>
            <div class="nk-block-des text-soft">
                <p>Uploader Dashboard.</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">                        
                        <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                    </ul>
                </div>
            </div>
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->



<div class="nk-block">
    <div class="row g-gs">
        <div class="col-lg-6 col-sm-6">
            <div class="card h-100 bg-primary">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">5</span>
                                    <span class="align-self-end fs-14px pb-1">
                                </div>
                                <h6 class="text-white">Total Transaksi Hari ini</h6>
                            </div>
                            <div class="card-tools me-n1">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-sm btn-trigger on-dark" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><span>Detail Laporan</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                    
                </div><!-- .nk-cmwg -->
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-lg-6 col-sm-6">
            <div class="card h-100 bg-info">
                <div class="nk-cmwg nk-cmwg1">
                    <div class="card-inner pt-3">
                        <div class="d-flex justify-content-between">
                            <div class="flex-item">
                                <div class="text-white d-flex flex-wrap">
                                    <span class="fs-2 me-1">199</span>
                                    <span class="align-self-end fs-14px pb-1">
                                </div>
                                <h6 class="text-white">Total Folder</h6>
                            </div>
                            <div class="card-tools me-n1">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-sm btn-trigger on-dark" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><span>Detail Laporan</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .card-inner -->
                    
                </div><!-- .nk-cmwg -->
            </div><!-- .card -->
        </div><!-- .col -->
        
    </div><!-- .row -->
</div><!-- .nk-block -->

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
      
    });
    
        
</script>
<?= $this->endSection() ?>