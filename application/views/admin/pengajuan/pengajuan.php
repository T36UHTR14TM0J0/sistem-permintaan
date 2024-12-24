<section class="content">
    <div class="container-fluid">
        <div class="container mt-4">
            <h2><?= $title ?></h2>
            <!-- Form Filter -->
            <form action="<?= base_url('admin/pengajuan') ?>" method="get" class="mb-3">
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
                    Tidak ada Pengajuan
                </div>
            <?php else: ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
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
                            <td><?= $p->nama_user ?></td>
                            <td><?= $p->nama_departement ?></td>
                            <td><?= $p->deskripsi ?></td>
                            <td><?= $p->tanggal_permintaan ? date('d-m-Y', strtotime($p->tanggal_permintaan)) : '-' ?></td>
                            <td>
                                <?php if ($p->status == 'Menunggu Diterima'): ?>
                                    <span class="badge bg-warning text-dark">Permintaan</span>
                                <?php elseif ($p->status == 'Diterima HRGA'): ?>
                                    <span class="badge bg-info"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Diterima PUD/Purchasing'): ?>
                                    <span class="badge bg-success"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Menunggu Pud/Purchasing'): ?>
                                    <span class="badge bg-warning"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Ditolak HRGA'): ?>
                                    <span class="badge bg-danger"><?= $p->status ?></span>
                                <?php elseif ($p->status == 'Ditolak PUD/Purchasing'): ?>
                                    <span class="badge bg-danger"><?= $p->status ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p->status == 'Diterima HRGA'): ?>
                                    <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-detail"><i class="ti ti-eye"></i></button>
                                    <button type="button" class="btn btn-success btn-sm" data-type="approve" data-id="<?= $p->id_permintaan ?>" id="btn-modal-approve"><i class="ti ti-check"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" data-type="tolak" data-id="<?= $p->id_permintaan ?>" id="btn-modal-tolak"><i class="ti ti-x"></i></button>
                                <?php elseif ( $p->status == 'Ditolak HRGA' || $p->status == 'Menunggu Pud/Purchasing' || $p->status == 'Diterima PUD/Purchasing'): ?>
                                    <button type="button" class="btn btn-info btn-sm" data-type="detail" data-id="<?= $p->id_permintaan ?>" id="btn-modal-detail"><i class="ti ti-eye"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
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
                    <h5 class="modal-title" id="approveModalLabel">Detail Pengajuan</h5>
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
                                <th width="25%">status</th>
                                <th width="25%">catatan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="mb-3" id="box-catatan">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" id="catatan" rows="3" ></textarea>
                        <span class="text-danger d-none" id="catatan-error">Catatan wajib diisi.</span>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-approve">Setujui</button>
                    <button type="submit" class="btn btn-danger" id="btn-reject">Tolak</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#btn-modal-detail, #btn-modal-approve, #btn-modal-tolak').on('click', function(e) {
            e.preventDefault();
            var type = $(this).data('type');
            var permintaanId = $(this).data('id');

            $('#catatan-error').addClass('d-none'); // Reset pesan error catatan
            $('#catatan').removeClass('is-invalid'); // Reset border merah

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
                    if (type == 'approve') {
                        $('#approveForm').attr('action', '<?= site_url('admin/ApprovePengajuan/') ?>' + permintaanId);
                        $('#btn-approve').show();
                        $('#btn-reject').hide();
                        $('#box-catatan').show();
                    } else if (type == 'tolak') {
                        $('#approveForm').attr('action', '<?= site_url('admin/tolak_pengajuan/') ?>' + permintaanId);
                        $('#btn-approve').hide();
                        $('#btn-reject').show();
                        $('#box-catatan').show();
                    } else {
                        $('#approveForm').attr('action', '');
                        $('#btn-approve').hide();
                        $('#btn-reject').hide();
                        $('#box-catatan').hide();
                    }

                    $('#approveModal').modal('show');
                },
                error: function() {
                    alert('Error fetching data');
                }
            });
        });

         // Validasi input catatan sebelum submit
         $('#btn-approve, #btn-reject').on('click', function (e) {
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
    });
</script>
