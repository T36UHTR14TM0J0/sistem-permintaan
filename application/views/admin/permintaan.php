<section class="content">
    <div class="container-fluid">
        <div class="container mt-4">
            <h2><?= $title ?></h2>
            <!-- Form Filter -->
            <form action="<?= base_url('admin/permintaan') ?>" method="get" class="mb-3">
                <div class="d-flex justify-content-between mb-3">
                    <div class="form-group col-md-8">
                        <input type="text" class="form-control" name="query" placeholder="Cari ..." value="<?= htmlspecialchars($this->input->get('query')) ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-info w-100">Filter</button>
                    </div>
                </div>
            </form>
            <?php if (empty($data)): ?>
                <div class="alert alert-warning" role="alert">
                    Tidak ada Permintaan
                </div>
            <?php else: ?>
            <!-- Tabel Data --> 
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-center">Nama Peminta</th>
                        <th class="text-center">Departemen</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Tanggal Permintaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $p): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= htmlspecialchars($p->nama_user) ?></td>
                            <td><?= htmlspecialchars($p->nama_departement) ?></td>
                            <td><?= htmlspecialchars($p->deskripsi) ?></td>
                            <td><?= $p->tanggal_permintaan ? date('d-m-Y', strtotime($p->tanggal_permintaan)) : '-' ?></td>
                            <td>
                                <?php if ($p->status == 'Menunggu Diterima'): ?>
                                    <span class="badge bg-warning text-dark">Permintaan</span>
                                <?php elseif ($p->status == 'Diterima HRGA'): ?>
                                    <span class="badge bg-warning">Menunggu PUD/Purchasing</span>
                                <?php elseif ($p->status == 'Diterima PUD/Purchasing'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span><br>
                                    <a href="<?= base_url('penjadwalan/penjadwalan');?>" class="btn btn-info btn-sm mt-2"><i class="ti ti-calendar"></i>Penjadwalan</a>
                                <?php elseif ($p->status == 'Menunggu Pud/Purchasing'): ?>
                                    <span class="badge bg-warning"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Ditolak HRGA'): ?>
                                    <span class="badge bg-danger"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Ditolak PUD/Purchasing'): ?>
                                    <span class="badge bg-danger"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Dijadwalkan HRGA'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Sudah Diterima Departement'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span>
                                    <?php elseif ($p->status == 'Batas'): ?>
                                        <span class="badge bg-danger">Permintaan melebihi batas 30 hari</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                // Hitung waktu sekarang
                                $waktu_sekarang = time(); // Waktu sekarang dalam detik
                                $waktu_permintaan = strtotime($p->tanggal_permintaan); // Konversi tanggal permintaan ke detik

                                // Hitung selisih waktu dalam hari
                                $selisih_hari = ($waktu_sekarang - $waktu_permintaan) / (60 * 60 * 24); // Konversi selisih ke hari

                                // Periksa apakah selisih lebih dari 30 hari
                                $isExpired = $selisih_hari > 30;
                                ?>

                                <?php if ($p->status == 'Menunggu Diterima'): ?>
                                    <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-approve"><i class="ti ti-eye"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" data-type="approve" data-id="<?= $p->id_permintaan ?>" id="btn-modal-approve"><i class="ti ti-check"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" data-type="tolak" data-id="<?= $p->id_permintaan ?>" id="btn-modal-tolak"><i class="ti ti-x"></i></button>
                                    <?php if ($isExpired): ?>
                                        <!-- Tombol Expired -->
                                        <button type="button" class="btn btn-warning btn-sm" data-type="expired" data-id="<?= $p->id_permintaan ?>" id="btn-modal-expired">
                                        <i class="ti ti-timer"></i> 30h Lebih
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-approve"><i class="ti ti-eye"></i></button>
                                <?php endif; ?>

                                <?php if ($p->status == 'Sudah Diterima Departement'): ?>
                                    <!-- Tombol untuk membuka modal -->
                                    <button type="button" class="btn btn-secondary btn-sm preview_image" data-id="<?= $p->id_permintaan ?>">
                                        <i class="ti ti-camera"></i> <!-- Ikon Image -->
                                    </button>
                                <?php endif; ?>
                            
                            </td>



                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
            <!-- Pagination -->
            <div class="pagination-wrapper">
                <?= $pagination ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post" id="approveForm" action="">
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
                                <th>Jumlah</th>
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
                                <th width="25%">status</th>
                                <th width="25%">catatan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- Inputan Catatan -->
                    <div class="mb-3" id="box-catatan">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                        <span class="text-danger d-none" id="catatan-error">Catatan wajib diisi.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-simpan">Setujui</button>
                    <button type="submit" class="btn btn-danger" id="btn-tolak">Tolak</button>
                    <button type="submit" class="btn btn-warning" id="btn-expired">Update</button>
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
        $('#btn-modal-approve, #btn-modal-tolak,#btn-modal-expired').on('click', function (e) {
            e.preventDefault();
            var type = $(this).data('type'); // Mendapatkan tipe aksi (detail, approve, atau tolak)
            var permintaanId = $(this).data('id');

            // Reset modal
            $('#btn-simpan').hide();
            $('#btn-tolak').hide();
            $('#btn-expired').hide();
            $('#catatan-error').addClass('d-none'); // Reset pesan error catatan
            $('#catatan').removeClass('is-invalid'); // Reset border merah

            $.ajax({
                url: '<?= site_url('admin/get_permintaan_data') ?>',
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
                        $('#approveForm').attr('action', '<?= site_url('admin/approve/') ?>' + permintaanId);
                        $('#approveModalLabel').text('Setujui Permintaan');
                        $('#box-catatan').show();
                    } else if (type === 'tolak') {
                        $('#btn-tolak').show();
                        $('#approveForm').attr('action', '<?= site_url('admin/tolak_hrga/') ?>' + permintaanId);
                        $('#approveModalLabel').text('Tolak Permintaan');
                        $('#box-catatan').show();
                    } else if (type === 'expired') {
                        $('#btn-expired').show();
                        $('#approveForm').attr('action', '<?= site_url('admin/expired/') ?>' + permintaanId);
                        $('#approveModalLabel').text('Permintaan melebihi batas 30 hari');
                        $('#box-catatan').show();
                    } else if (type === 'detail') {
                        $('#approveModalLabel').text('Detail Permintaan');
                        $('#box-catatan').hide();
                    }

                    $('#approveModal').modal('show');
                },
                error: function () {
                    alert('Gagal mengambil data.');
                },
            });
        });

        // Validasi input catatan sebelum submit
        $('#btn-simpan, #btn-tolak').on('click', function (e) {
            var catatan = $('#catatan').val().trim();
            if (catatan === '') {
                e.preventDefault();
                $('#catatan-error').removeClass('d-none'); // Tampilkan pesan error
                $('#catatan').addClass('is-invalid'); // Tambahkan border merah
                return false;
            } else {
                $('#catatan-error').addClass('d-none'); // Sembunyikan pesan error
                $('#catatan').removeClass('is-invalid'); // Hapus border merah
            }
        });

        // Reset error ketika pengguna mengetik
        $('#catatan').on('input', function () {
            if ($(this).val().trim() !== '') {
                $('#catatan-error').addClass('d-none');
                $(this).removeClass('is-invalid');
            }
        });

        // Menangani klik pada tombol preview image
        $('.preview_image').on('click', function (e) {
            e.preventDefault();

            var permintaanId = $(this).data('id');

            $.ajax({
                url: '<?= site_url('Permintaan/get_image_data') ?>', // URL untuk mendapatkan data gambar
                method: 'GET',
                data: { id: permintaanId },
                success: function (response) {
                    var data = JSON.parse(response);

                    // Pastikan response mengandung URL gambar atau path yang benar
                    var imageUrl = '<?= base_url('uploads/validasi/') ?>' + data.image_url; // Gabungkan base_url() dengan path gambar

                    console.log(imageUrl); // Pastikan URL yang benar

                    // Set gambar pada modal
                    $('#modal_preview_image').attr('src', imageUrl);

                    // Tampilkan modal
                    $('#previewImageModal').modal('show');
                },
                error: function () {
                    alert('Gagal memuat gambar.');
                },
            });
        });
    });
</script>
