<section class="content">
    <div class="container-fluid">
        <div class="container mt-4">
            <h2><?= $title ?></h2>

            <!-- Filter Form -->
            <form method="get" action="<?= site_url('Penjadwalan/penjadwalan') ?>">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select name="filter_type" class="form-control">
                            <option value="all" <?= $filter_type == 'all' ? 'selected' : '' ?>>Semua</option>
                            <option value="nama_user" <?= $filter_type == 'nama_user' ? 'selected' : '' ?>>Nama Peminta</option>
                            <option value="nama_departement" <?= $filter_type == 'nama_departement' ? 'selected' : '' ?>>Departemen</option>
                            <option value="deskripsi" <?= $filter_type == 'deskripsi' ? 'selected' : '' ?>>Keterangan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="filter_value" class="form-control" placeholder="Cari berdasarkan..." value="<?= $filter_value ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="bulan_penjadwalan" class="form-control">
                            <option value="">Pilih Bulan</option>
                            <?php 
                            // Array bulan dalam bahasa Indonesia
                            $bulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ];

                            // Menampilkan dropdown bulan
                            for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $bulan_penjadwalan == $i ? 'selected' : '' ?>>
                                    <?= $bulan[$i] ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>


                    <div class="col-md-2">
                        <select name="tahun_penjadwalan" class="form-control">
                            <option value="">Tahun</option>
                            <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                                <option value="<?= $i ?>" <?= $tahun_penjadwalan == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Tabel Data -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Peminta</th>
                        <th>Departemen</th>
                        <th>Tanggal Permintaan</th>
                        <th>Tanggal Penjadwalan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $p): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $p->nama_user ?></td>
                            <td><?= $p->nama_departement ?></td>
                            <td><?= $p->tanggal_permintaan ? date('d-m-Y', strtotime($p->tanggal_permintaan)) : '-' ?></td>
                            <td><?= $p->tanggal_penjadwalan ? date('d-m-Y', strtotime($p->tanggal_penjadwalan)) : '-' ?></td>
                            <td>
                                <?php if ($p->status == 'Menunggu Diterima'): ?>
                                    <span class="badge bg-warning text-dark"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Diterima PUD/Purchasing'): ?>
                                    <span class="badge bg-secondary"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Dijadwalkan HRGA'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Sudah Diterima Departement'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p->status == 'Diterima PUD/Purchasing'): ?>
                                    <button type="button" class="btn btn-success btn-sm btn-modal-approve" data-id="<?= $p->id_permintaan ?>"><i class="ti ti-calendar"></i></button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-detail"><i class="ti ti-eye"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?= $pagination ?>

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
                                <th style="width: 25%;">Tanggal</th>
                                <th style="width: 25%;">Nama User</th>
                                <th style="width: 25%;">status</th>
                                <th style="width: 25%;">catatan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Form Tambahan untuk Tanggal dan Keterangan -->
                    <div class="form-group mt-3">
                        <label for="tanggal_jadwal">Tanggal Jadwal</label>
                        <input type="date" class="form-control" id="tanggal_jadwal" name="tanggal_jadwal" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="keterangan_jadwal">Keterangan</label>
                        <textarea class="form-control" id="keterangan_jadwal" name="keterangan_jadwal" rows="3" required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-approve">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#btn-modal-detail, .btn-modal-approve').on('click', function(e) {
        e.preventDefault();
        var permintaanId = $(this).data('id');
        var type = $(this).data('type');
        
        $.ajax({
            url: '<?= site_url('admin/get_permintaan_data') ?>',
            method: 'GET',
            data: { id: permintaanId },
            success: function(response) {
                var data = JSON.parse(response);
                $('#nama_peminta').text(data.nama_peminta);
                $('#tanggal_permintaan').text(data.tanggal_permintaan);
                $('#nama_departement').text(data.nama_departement);
                $('#deskripsi').text(data.deskripsi);

                var barangDetailsHtml = '';
                $.each(data.detail_barang, function(index, item) {
                    barangDetailsHtml += '<tr>';
                    barangDetailsHtml += '<td>' + item.nama_barang + '</td>';
                    barangDetailsHtml += '<td>' + item.jumlah + ' ' + item.satuan + '</td>';
                    barangDetailsHtml += '</tr>';
                });
                $('#barang_details_table tbody').html(barangDetailsHtml);

                var historiDetailsHtml = '';
                $.each(data.log_details, function(index, item) {
                    historiDetailsHtml += '<tr>';
                    historiDetailsHtml += '<td>' + item.tanggal_log + '</td>';
                    historiDetailsHtml += '<td>' + item.nama_user + '</td>';
                    historiDetailsHtml += '<td>' + item.status + '</td>';
                    historiDetailsHtml += '<td>' + item.catatan + '</td>';
                    historiDetailsHtml += '</tr>';
                });
                $('#barang_histori_table tbody').html(historiDetailsHtml);

                // Menyusun action URL berdasarkan permintaanId
                $('#approveForm').attr('action', '<?= site_url('Penjadwalan/Proses_Penjadwalan/') ?>' + permintaanId);
                $('#approveModal').modal('show');
                $('.form-group').show();
                $('#btn-approve').show();
                if (type == 'detail') {
                   $('.form-group').hide();
                   $('#btn-approve').hide();
                }

                // Set minimum date for the jadwal field after modal is opened
                setMinDate(data.tanggal_permintaan);  // Pass tanggal_permintaan to setMinDate
            },
            error: function() {
                alert('Error fetching data');
            }
        });
    });
});

// Function to set the minimum date for jadwal
function setMinDate(tanggalPermintaan) {
    if (tanggalPermintaan) {
        const tanggalJadwal = document.getElementById("tanggal_jadwal");

        // Format tanggal permintaan to YYYY-MM-DD
        const formattedDate = formatDate(tanggalPermintaan);

        // Set the min date for the jadwal input to the tanggal_permintaan
        tanggalJadwal.setAttribute('min', formattedDate);
        tanggalJadwal.value = formattedDate;
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
    