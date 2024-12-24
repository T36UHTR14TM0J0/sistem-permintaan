
<section id="daftar_permintaan" class="section mt-5 mb-5">
    <div class="container">
        <h2 class="text-center">Daftar Permintaan Saya</h2>
        <!-- Form Filter -->
        <form action="<?= base_url('Permintaan/daftar_permintaan') ?>" method="get" class="mb-3">
        <div class="d-flex justify-content-center align-items-center mb-3">
            <div class="row w-100">
                <!-- Filter Jenis -->
                <div class="col-md-2">
                    <label for="filter_type">Filter Berdasarkan</label>
                    <select id="filter_type" name="filter_type" class="form-control">
                        <option value="all" <?= $filter_type == 'all' ? 'selected' : '' ?>>Semua</option>
                        <option value="nama_departement" <?= $filter_type == 'nama_departement' ? 'selected' : '' ?>>Departemen</option>
                    </select>
                </div>

                <!-- Filter Nilai -->
                <div class="col-md-3">
                    <label for="filter_value">Kata Kunci</label>
                    <input id="filter_value" type="text" name="filter_value" class="form-control" placeholder="Cari berdasarkan..." value="<?= $filter_value ?>">
                </div>

                <!-- Tanggal Awal -->
                <div class="col-md-3">
                    <label for="tanggal_awal">Dari</label>
                    <input id="tanggal_awal" type="date" name="tanggal_awal" class="form-control" value="<?= $tanggal_awal ?>">
                </div>

                <!-- Tanggal Akhir -->
                <div class="col-md-3">
                    <label for="tanggal_akhir">Sampai</label>
                    <input id="tanggal_akhir" type="date" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?>">
                </div>

                <!-- Tombol Filter -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" style="margin-right: 10px;">Filter</button>
                    <button type="submit" id="reset_filter" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </div>


        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tanggal Permintaan</th>
                    <th class="text-center">Departemen</th>
                    <th class="text-center">Tanggal Jadwal</th>
                    <th class="text-center">Tanggal Terima</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($daftar_permintaan)): ?>
                <?php $no = 1; ?>
                <?php foreach ($daftar_permintaan as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($p->tanggal_permintaan)); ?></td>
                        <td><?= $p->nama_departement; ?></td>
                        <td>
                            <?php 
                            // Periksa apakah tanggal_jadwal ada dan valid
                            if (empty($p->tanggal_jadwal) || strtotime($p->tanggal_jadwal) === false) {
                                echo "-"; // Tampilkan "-" jika tanggal kosong atau tidak valid
                            } else {
                                echo date('d-m-Y', strtotime($p->tanggal_jadwal)); // Tampilkan tanggal jika valid
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            // Periksa apakah tanggal_jadwal ada dan valid
                            if (empty($p->tanggal_diterima) || strtotime($p->tanggal_diterima) === false) {
                                echo "-"; // Tampilkan "-" jika tanggal kosong atau tidak valid
                            } else {
                                echo date('d-m-Y', strtotime($p->tanggal_diterima)); // Tampilkan tanggal jika valid
                            }
                            ?>
                        </td>

                        <td><?= $p->deskripsi; ?></td> 
                        <td>
                            <?php 
                            // Menampilkan status dengan badge sesuai status permintaan
                            $status_class = '';
                            switch ($p->status) {
                                case 'Menunggu Diterima':
                                    $status_class = 'bg-warning text-dark';
                                    break;
                                case 'Diterima HRGA':
                                    $status_class = 'bg-warning';
                                    break;
                                case 'Diterima PUD/Purchasing':
                                    $status_class = 'bg-success';
                                    break;
                                case 'Menunggu Pud/Purchasing':
                                    $status_class = 'bg-warning';
                                    break;
                                case 'Ditolak HRGA':
                                case 'Ditolak PUD/Purchasing':
                                    $status_class = 'bg-danger';
                                    break;
                                case 'Dijadwalkan HRGA':
                                    $status_class = 'bg-success';
                                    break;
                                case 'Sudah Diterima Departement':
                                    $status_class = 'bg-success';
                                    break;
                                case 'Batas':
                                    $status_class = 'bg-danger';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $status_class ?>"><?= ($p->status=='Batas') ? 'Permintaan melebihi batas 30 hari' : $p->status ?></span>
                        </td>
                        <td>
                            <?php if ($p->status == 'Dijadwalkan HRGA'): ?>
                                <button type="button" class="btn btn-info btn-sm btn-modal " data-type="detail" data-id="<?= $p->id_permintaan ?>">
                                    <i class="bi bi-eye text-light"></i> <!-- Ikon Eye dari Bootstrap Icons -->
                                </button>
                                <button type="button" class="btn btn-success btn-sm btn-modal" data-type="approve" data-id="<?= $p->id_permintaan ?>">
                                    <i class="bi bi-check-circle"></i> <!-- Ikon Check Circle dari Bootstrap Icons -->
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-info btn-sm btn-modal" data-type="detail" data-id="<?= $p->id_permintaan ?>">
                                    <i class="bi bi-eye text-light"></i> <!-- Ikon Eye dari Bootstrap Icons -->
                                </button>
                               
                            <?php endif; ?>
                            <?php if ($p->status == 'Sudah Diterima Departement'): ?>
                                <!-- Tombol untuk membuka modal -->
                                <button type="button" class="btn btn-secondary btn-sm preview_image" data-id="<?= $p->id_permintaan ?>">
                                    <i class="bi bi-image"></i> <!-- Ikon Image -->
                                </button>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada permintaan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <?= $pagination ?>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post" id="approveForm" action="" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Detail Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="50%">Nama Peminta</th>
                            <td id="nama_peminta"></td>
                        </tr>
                        <tr>
                            <th width="50%">Tanggal Permintaan</th>
                            <td id="tanggal_permintaan"></td>
                        </tr>
                        <tr>
                            <th width="50%">Nama Departemen</th>
                            <td id="nama_departement"></td>
                        </tr>
                        <tr>
                            <th width="50%">Keterangan</th>
                            <td id="deskripsi"></td>
                        </tr>
                    </table>
                    <h5>Daftar Barang Yang Diminta</h5>
                    <table id="barang_details_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50%">Nama Barang</th>
                                <th width="50%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <h5>Histori Log Permintaan</h5>
                    <table id="barang_histori_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="25%">Tanggal</th>
                                <th width="25%">Nama User</th>
                                <th width="25%">Status</th>
                                <th width="25%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- Inputan Catatan -->
                    <div class="mb-3" id="box-catatan">
                        <label for="catatan" class="form-label">Tanggal Terima</label>
                        <input type="date" class="form-control" name="tanggal_diterima" id="tanggal_diterima" required>
                    </div>
                    
                    <!-- Input Foto -->
                    <div class="mb-3" id="box-file">
                        <label for="upload_foto" class="form-label">Upload Foto</label>
                        <input type="file" class="form-control" name="upload_foto" id="upload_foto" accept="image/*">
                        <small class="form-text text-muted">Hanya file gambar (JPG, PNG, JPEG) yang diperbolehkan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="previewImageModal" tabindex="-1" aria-labelledby="previewImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewImageModalLabel">Preview Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modal_preview_image" src="" alt="Preview Gambar" class="img-fluid" />
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#reset_filter').click(function () {
            // Reset form input
            $('#filter_type').val('all');
            $('#filter_value').val('');
            $('#tanggal_awal').val('');
            $('#tanggal_akhir').val('');

            
        });

        // Saat tanggal awal diubah
        $('#tanggal_awal').on('change', function () {
                var tanggalAwal = $(this).val();
                
                if (tanggalAwal) {
                    // Set atribut min pada tanggal akhir
                    $('#tanggal_akhir').attr('min', tanggalAwal);
                } else {
                    // Hapus atribut min jika tanggal awal dikosongkan
                    $('#tanggal_akhir').removeAttr('min');
                }
            });
        // function scrollToSection() {
        //     // Menambahkan hash ke URL
        //     window.location.hash = '#daftar_permintaan';
        // }
        $('#preloader').hide();
        $('.btn-modal').on('click', function (e) {
            e.preventDefault();
            var type = $(this).data('type'); // Mendapatkan tipe aksi (detail, approve, atau tolak)
            var permintaanId = $(this).data('id');

            // Reset modal
            $('#btn-simpan').hide();
            $('#btn-tolak').hide();

            $.ajax({
                url: '<?= site_url('Permintaan/get_permintaan_data') ?>',
                method: 'GET',
                data: { id: permintaanId },
                success: function (response) {
                    var data = JSON.parse(response);

                    // Isi data ke dalam modal
                    $('#nama_peminta').text(data.nama_peminta);
                    $('#tanggal_permintaan').text(data.tanggal_permintaan);
                    $('#nama_departement').text(data.nama_departement);
                    $('#deskripsi').text(data.deskripsi);

                    // Daftar barang
                    var barangDetailsHtml = '';
                    $.each(data.detail_barang, function (index, item) {
                        barangDetailsHtml += '<tr>';
                        barangDetailsHtml += '<td>' + item.nama_barang + '</td>';
                        barangDetailsHtml += '<td>' + item.jumlah + ' ' + item.satuan + '</td>';
                        barangDetailsHtml += '</tr>';
                    });
                    $('#barang_details_table tbody').html(barangDetailsHtml);

                    // Daftar histori
                    var historiDetailsHtml = '';
                    $.each(data.log_details, function (index, item) {
                        historiDetailsHtml += '<tr>';
                        historiDetailsHtml += '<td>' + item.tanggal_log + '</td>';
                        historiDetailsHtml += '<td>' + item.nama_user + '</td>';
                        historiDetailsHtml += '<td>' + item.status + '</td>';
                        historiDetailsHtml += '<td>' + item.catatan + '</td>';
                        historiDetailsHtml += '</tr>';
                    });
                    $('#barang_histori_table tbody').html(historiDetailsHtml);

                    // Tampilkan tombol sesuai tipe aksi
                    if (type === 'approve') {
                        $('#btn-simpan').show().text('Setujui');
                        $('#approveForm').attr('action', '<?= site_url('permintaan/validasi/') ?>' + permintaanId);
                        $('#approveModalLabel').text('Validasi Permintaan');
                        $('#box-catatan').show();
                        $('#box-file').show();
                    } else if (type === 'detail') {
                        $('#approveModalLabel').text('Detail Permintaan');
                        $('#box-catatan').hide();
                        $('#box-file').hide();
                    }
                    setMinDate(data.tanggal_jadwal);
                    $('#approveModal').modal('show');
                },
                error: function () {
                    alert('Gagal mengambil data.');
                }
            });
        });

        // Menangani klik pada tombol preview image
        $('.preview_image').on('click', function (e) {
            e.preventDefault();

            var permintaanId = $(this).data('id');
            
            $.ajax({
                url: '<?= site_url('Permintaan/get_image_data') ?>', // URL untuk mendapatkan data gambar
                method: 'GET',
                data: { id: permintaanId },
                success: function(response) {
                    var data = JSON.parse(response);

                    // Pastikan response mengandung URL gambar atau path yang benar
                    var imageUrl = '<?= base_url('uploads/validasi/') ?>' + data.image_url;  // Gabungkan base_url() dengan path gambar

                    console.log(imageUrl);  // Pastikan URL yang benar

                    // Set gambar pada modal
                    $('#modal_preview_image').attr('src', imageUrl);

                    // Tampilkan modal
                    $('#previewImageModal').modal('show');
                },
                error: function() {
                    alert('Gagal memuat gambar.');
                }
            });


        });
    });

    
// Function to set the minimum date for jadwal
function setMinDate(tanggalPermintaan) {
    if (tanggalPermintaan) {
        const tanggalDiterima = document.getElementById("tanggal_diterima");

        // Format tanggal permintaan to YYYY-MM-DD
        const formattedDate = formatDate(tanggalPermintaan);

        // Set the min date for the jadwal input to the tanggal_permintaan
        tanggalDiterima.setAttribute('min', formattedDate);
        tanggalDiterima.value = formattedDate;
    }
}

// Format date to YYYY-MM-DD
function formatDate(dateString) {
    var date = new Date(dateString);
    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    return year + "-" + month + "-" + day;
}

   

</script>
