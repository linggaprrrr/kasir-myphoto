<?= $this->extend('components/layout') ?>

<?= $this->section('content') ?>
<style>
    /* Agar "Show entries" dan "Search" tidak bertabrakan dengan card */
  

    /* Memastikan wrapper card tidak overlapping */
    .card {
        overflow: hidden; /* Ini mencegah elemen di dalam card keluar */
        padding: 15px; /* Menambahkan padding agar tidak terlalu rapat */
    }

   
    

    .dataTables_filter {
        float: right;
        text-align: right;
    }
</style>



<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Directories</h3>
            <div class="nk-block-des text-soft">
                
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFormModal">                
                <span>Create Folder</span>
            </button>
                <!-- Modal Form -->
                <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus="false">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="FolderForm" class="form-validate is-alter" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Folder Info</h5>
                                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <em class="icon ni ni-cross"></em>
                                    </a>
                                </div>
                                <div class="modal-body">                                                
                                    <div class="form-group">
                                        <label class="form-label" for="full-name">Nama Folder (Kode Transaksi)*</label>
                                        <div class="form-control-wrap">                                            
                                            <input type="text" name="kode_transaksi" class="form-control"  required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="full-name">Nama Customer</label>
                                        <div class="form-control-wrap">                                            
                                            <input type="text" name="user" class="form-control">
                                        </div>
                                    </div>                                                                       
                                    <div class="form-group">
                                        <label class="form-label" for="full-name">Nama Konter</label>
                                        <div class="form-control-wrap">                                            
                                            <input type="text" name="cabang" class="form-control">
                                        </div>
                                    </div>                                                                         
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="submit" class="btn btn-lg btn-secondary">Add Folder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-content-body">
    <div class="card card-bordered card-preview">
        <div class="card-inner">
            <div class="table-responsive">
                <table class="datatable-init2 table table-hover" data-auto-responsive="false">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">                        
                        <th class="nk-tb-col text-center"><span class="sub-text">No</span></th>                        
                        <th class="nk-tb-col text-center"><span class="sub-text">Directory</span></th> 
                        <th class="nk-tb-col text-center"><span class="sub-text">Konter</span></th>                        
                        <th class="nk-tb-col text-center"><span class="sub-text">Customer</span></th>                        
                        <th class="nk-tb-col text-center"><span class="sub-text">Total Photo</span></th>                        
                        <th class="nk-tb-col text-center"><span class="sub-text">Status</span></th>                        
                        <th class="nk-tb-col text-center"><span class="sub-text">Created At</span></th>                        
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
    </div><!-- .card-preview -->
</div>


<div class="nk-block">
    
</div><!-- .nk-block -->

<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    table = $('.datatable-init2').DataTable({            
        "bInfo": false,
        "ordering": false, // Menonaktifkan sorting di semua kolom
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('/load-dirs') ?>",
            "dataType": "json",
            "type": "POST",
        },
        "columns": [                
            // Kolom nomor index, text-center
            { 
                "data": null, 
                render: function(data, type, row, meta) {
                    return `<span class="text-center">${meta.row + meta.settings._iDisplayStart + 1}</span>`; // No
                },
                "className": "text-center"
            },
            // Kolom kode_transaksi, text-left
            { 
                "data": "kode_transaksi",
                render: function(data, type, row) {
                    return `
                        <div style="display: ruby;">
                            <a href="/dir/${row.kode_transaksi}">
                                <div class="nk-file-icon">
                                    <span class="nk-file-icon-type">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72">
                                            <g>
                                                <rect x="32" y="16" width="14" height="7" rx="2.5" ry="2.5" style="fill:#f29611"></rect>
                                                <path d="M59.7778,61H12.2222A6.4215,6.4215,0,0,1,6,54.3962V17.6038A6.4215,6.4215,0,0,1,12.2222,11H30.6977a4.6714,4.6714,0,0,1,4.1128,2.5644L38,24H59.7778A5.91,5.91,0,0,1,66,30V54.3962A6.4215,6.4215,0,0,1,59.7778,61Z" style="fill:#ffb32c"></path>
                                                <path d="M8.015,59c2.169,2.3827,4.6976,2.0161,6.195,2H58.7806a6.2768,6.2768,0,0,0,5.2061-2Z" style="fill:#f2a222"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div class="nk-file-name">
                                    <div class="nk-file-name-text">
                                        <span class="title">${row.kode_transaksi}</span>
                                    </div>
                                </div>
                            </a>
                        </div>`;
                },
                "className": "text-left" // Hanya kolom ini yang align left
            },
            // Kolom cabang, text-center
            { 
                "data": "cabang",
                render: function(data, type, row) {
                    return `<span class="text-center">${row.cabang}</span>`;
                },
                "className": "text-center"
            },
            // Kolom user, text-center
            { 
                "data": "user",
                render: function(data, type, row) {
                    return `<span class="text-center">${row.user}</span>`;
                },
                "className": "text-center"
            },
            // Kolom total_photo dengan badge dinamis, text-center
            { 
                "data": "total_photo",
                render: function(data, type, row) {
                    if (row.total_photo > 0) {
                        return `<span class="badge bg-primary text-center">${row.total_photo}</span>`;
                    } else {
                        return `<span class="badge bg-warning text-center">Waiting</span>`;
                    }
                },
                "className": "text-center"
            },
            // Kolom status dengan kondisi expired atau days left, text-center
            { 
                "data": "created_at",
                render: function(data, type, row) {
                    let created_at = new Date(row.created_at);
                    let current_time = new Date();
                    let diff_in_days = Math.floor((current_time - created_at) / (1000 * 60 * 60 * 24)); // Menghitung selisih hari
                    
                    if (row.status == 1) {
                        if (diff_in_days < 5) {
                            let days_left = 5 - diff_in_days;
                            return `<span class="badge badge-dot bg-info text-center">${days_left} days left</span>`;
                        } else {
                            return `<span class="badge badge-dot bg-danger text-center">Expired</span>`;
                        }
                    } else {
                        return `<span class="badge badge-dot bg-danger text-center">Expired</span>`;
                    }
                },
                "className": "text-center"
            },
            // Kolom created_at dengan format tanggal, text-center
            { 
                "data": "created_at",
                render: function(data, type, row) {
                    return `<span class="form-control-sm text-center">${new Date(row.created_at).toLocaleString('en-GB', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</span>`;
                },
                "className": "text-center"
            }
        ]
    });

    // Tangkap event submit form
    $('#FolderForm').on('submit', function (e) {
        e.preventDefault(); // Mencegah refresh halaman default

        // Ambil data dari form
        let formData = new FormData(this);

        // Kirim AJAX request
        $.ajax({
            url: 'api/create-dir', // Endpoint API
            type: 'POST',
            data: formData,
            processData: false, // Jangan proses data
            contentType: false, // Jangan set header content-type secara manual
            beforeSend: function () {
                // Tampilkan loader atau nonaktifkan tombol submit
                $("#FolderForm button[type='submit']").prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                // Tampilkan notifikasi berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Folder berhasil dibuat!'
                });

                // Reset form
                $('#FolderForm')[0].reset();
                
                // Tutup modal (jika modal digunakan)
                $('#FolderForm').closest('.modal').modal('hide');

                // Refresh DataTable
                if ($.fn.DataTable.isDataTable('.datatable-init2')) {
                    $('.datatable-init2').DataTable().ajax.reload(null, false); // Reload tanpa reset paging
                }
            },
            error: function (xhr, status, error) {
                // Tampilkan notifikasi error
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan, silakan coba lagi.'
                });
            },
            complete: function () {
                // Aktifkan kembali tombol submit
                $("#FolderForm button[type='submit']").prop('disabled', false).text('Add Folder');
            }
        });
    });

});
</script>

<?= $this->endSection() ?>