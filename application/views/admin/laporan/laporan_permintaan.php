<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Laporan Permintaan</h5>

                        <form action="<?= base_url('Laporan/laporan_permintaan') ?>" method="get" class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <!-- Filter Tanggal Awal -->
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="tanggal_awal">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal" value="<?= $this->input->get('tanggal_awal') ?: date('Y-m-d') ?>" onchange="setMinDate()">
                                </div>

                                <!-- Filter Tanggal Akhir -->
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="tanggal_akhir">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" value="<?= $this->input->get('tanggal_akhir') ?: date('Y-m-d') ?>" min="<?= $this->input->get('tanggal_awal') ?: date('Y-m-d') ?>">
                                </div>

                                <!-- Filter Departemen -->
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="departemen">Departemen</label>
                                    <select class="form-control" name="departemen" id="departemen">
                                        <option value="">-- Semua Departemen --</option>
                                        <?php foreach ($departemen as $dept): ?>
                                            <option value="<?= $dept->id_departement ?>" <?= $this->input->get('departemen') == $dept->id_departement ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dept->nama_departement) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Tombol Filter -->
                                <div class="form-group w-25" style="margin-right: 10px; margin-top: 20px;">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>

                                <!-- Tombol Cetak -->
                                <div class="form-group w-10" style="margin-top: 20px; margin-right: 10px;">
                                    <a href="<?= site_url('Laporan/cetak_laporan_permintaan_pdf') . '?' . http_build_query($this->input->get()) ?>" class="btn btn-primary"><i class="fa fa-print"></i></a>
                                </div>

                                <!-- Tombol Hapus Filter -->
                                <div class="form-group w-10" style="margin-top: 20px;">
                                    <a href="<?= base_url('Laporan/laporan_permintaan') ?>" class="btn btn-danger w-100"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </form>
                       
                        <?php if (empty($data)): ?>
                            <div class="alert alert-warning" role="alert">
                                Data tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive" id="printableArea">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peminta</th>
                                            <th>Departemen</th>
                                            <!-- <th>Keterangan</th> -->
                                            <th>Tanggal Permintaan</th>
                                            <th>Tanggal Jadwal</th>
                                            <th>Tanggal Terima</th>
                                            <th class="no_print">Status</th>
                                            <th class="no_print" width="5">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_permintaan = 0;
                                        foreach ($data as $key => $p): 
                                            $total_permintaan++;
                                        ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= htmlspecialchars($p->nama_user) ?></td>
                                                <td><?= htmlspecialchars($p->nama_departement) ?></td>
                                                <!-- <td><?= htmlspecialchars($p->deskripsi) ?></td> -->
                                                <td><?= $p->tanggal_permintaan ? date('d-m-Y', strtotime($p->tanggal_permintaan)) : '-' ?></td>
                                                <td><?= $p->tanggal_penjadwalan ? date('d-m-Y', strtotime($p->tanggal_penjadwalan)) : '-' ?></td>
                                                <td><?= $p->tanggal_diterima ? date('d-m-Y', strtotime($p->tanggal_diterima)) : '-' ?></td>
                                                <td class="no_print">
                                                    <?php if ($p->status == 'Menunggu Diterima'): ?>
                                                        <span class="badge bg-warning text-dark">Permintaan</span>
                                                    <?php elseif ($p->status == 'Diterima HRGA'): ?>
                                                        <span class="badge bg-warning">Menunggu PUD/Purchasing</span>
                                                    <?php elseif ($p->status == 'Diterima PUD/Purchasing'): ?>
                                                        <span class="badge bg-success"><?= $p->status ?></span>
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
                                                <td class="no_print">
                                                    <?php if ($p->status == 'Menunggu Diterima'): ?>
                                                        <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-approve"><i class="ti ti-eye"></i></button>
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
                                        <tr>
                                            <th style="text-align: center;" colspan="7">Total Permintaan</th>
                                            <td style="text-align: center;"><?= $total_permintaan ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <h5>Total Permintaan: <?= $total_permintaan ?></h5> -->
                        <?php endif; ?>

                        <div class="pagination-wrapper">
                            <?= $pagination ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post" id="approveForm" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Jadwal Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 50%;">Nama Peminta</th>
                            <td id="nama_peminta"></td>
                        </tr>
                        <tr>
                            <th style="width: 50%;">Tanggal Permintaan</th>
                            <td id="tanggal_permintaan"></td>
                        </tr>
                        <tr>
                            <th style="width: 50%;">Nama Departemen</th>
                            <td id="nama_departement"></td>
                        </tr>
                        <tr>
                            <th style="width: 50%;">Keterangan</th>
                            <td id="deskripsi"></td>
                        </tr>
                    </table>
                    <h5>Daftar Barang Yang Diminta</h5>
                    <table id="barang_details_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Nama Barang</th>
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

                </div>
                <div class="modal-footer">
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
        $('#btn-modal-approve, #btn-modal-tolak').on('click', function (e) {
            e.preventDefault();
            var type = $(this).data('type'); // Mendapatkan tipe aksi (detail, approve, atau tolak)
            var permintaanId = $(this).data('id');

            // Reset modal
            $('#btn-simpan').hide();
            $('#btn-tolak').hide();

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
                    // Daftar barang
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

                    $('#approveModalLabel').text('Detail Permintaan');
                    $('#approveModal').modal('show');
                },
                error: function () {
                    alert('Gagal mengambil data.');
                },
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
</script>


<script>
    // Function to set min date of tanggal_akhir based on tanggal_awal
    function setMinDate() {
        const tanggalAwal = document.getElementById("tanggal_awal").value;
        const tanggalAkhir = document.getElementById("tanggal_akhir");

        if (tanggalAwal) {
            tanggalAkhir.setAttribute('min', tanggalAwal);
        }
    }
    window.onload = function() {
        setMinDate();
    };
</script>

