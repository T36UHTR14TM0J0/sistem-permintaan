<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Laporan Barang</h5>

                        <form action="<?= base_url('Laporan/laporan_barang') ?>" method="get" class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="tanggal_awal">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal" value="<?= $this->input->get('tanggal_awal') ?: date('Y-m-d') ?>" onchange="setMinDate()">
                                </div>

                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="tanggal_akhir">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" value="<?= $this->input->get('tanggal_akhir') ?: date('Y-m-d') ?>" min="<?= $this->input->get('tanggal_awal') ?: date('Y-m-d') ?>">
                                </div>
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <label for="jenis">Jenis</label>
                                    <select class="form-control" name="jenis" id="jenis">
                                        <option value="">-- Pilih --</option>
                                        <option value="masuk" <?= $this->input->get('jenis') == 'masuk' ? 'selected' : '' ?>>
                                               Masuk
                                        </option>
                                        <option value="keluar" <?= $this->input->get('jenis') == 'keluar' ? 'selected' : '' ?>>
                                                Keluar
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group w-25" style="margin-right: 10px;margin-top:20px;">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>

                                <div class="form-group w-10" style="margin-top:20px;margin-right: 10px;">
                                    <a href="<?= site_url('Laporan/cetak_laporan_barang_pdf') . '?' . http_build_query($this->input->get()) ?>" class="btn btn-primary w-100"><i class="fa fa-print"></i></a>
                                </div>
                                <!-- Tombol Hapus Filter -->
                                <div class="form-group w-10" style="margin-top: 20px;">
                                    <a href="<?= base_url('Laporan/laporan_barang') ?>" class="btn btn-danger w-100"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </form>

                        <?php if (empty($laporan_barang)): ?>
                            <div class="alert alert-warning" role="alert">
                                Barang tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive" id="printableArea">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Jumlah</th>
                                            <th>Nama User</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total_barang_keluar = 0; $total_barang_masuk = 0; ?>
                                        <?php $no = 1; foreach ($laporan_barang as $b): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $b->tanggal ? date('d-m-Y', strtotime($b->tanggal)) : '-' ?></td>
                                            <td><?= ucfirst($b->nama_barang) ?></td>
                                            <td><?= $b->kategori ?></td>
                                            <td><?= $b->jumlah.' '.$b->satuan; ?></td>
                                            <td><?= $b->user_name ?></td>
                                            <td>
                                
                                                <?php if ($b->status == 'masuk'): ?>
                                                    <span class="badge bg-success text-dark"><?=$b->status?></span>
                                                <?php elseif ($b->status == 'keluar'): ?>
                                                    <span class="badge bg-danger"><?= $b->status ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($b->bukti): ?>
                                                    <a href="<?= base_url('uploads/bukti/' . $b->bukti) ?>" target="_blank">Lihat Bukti</a>
                                                <?php else: ?>
                                                    - <!-- Tidak ada bukti -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php 
                                            if ($b->status == 'masuk') {
                                                $total_barang_masuk += $b->jumlah;
                                            } elseif ($b->status == 'keluar') {
                                                $total_barang_keluar += $b->jumlah;
                                            }
                                        ?>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th colspan="7" class="text-center">Jumlah Barang Masuk</th>
                                            <td><?= $total_barang_masuk;?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="text-center">Jumlah Barang Keluar</th>
                                            <td><?= $total_barang_keluar;?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
<script>
    // Function to set min date of tanggal_akhir based on tanggal_awal
    function setMinDate() {
        const tanggalAwal = document.getElementById("tanggal_awal").value;
        const tanggalAkhir = document.getElementById("tanggal_akhir");
        
        // Set the min attribute of tanggal_akhir based on tanggal_awal
        if (tanggalAwal) {
            tanggalAkhir.setAttribute('min', tanggalAwal);
        }
    }

    // Initialize the min date for tanggal_akhir and make sure it matches the tanggal_awal
    window.onload = function() {
        setMinDate();
    };
</script>
