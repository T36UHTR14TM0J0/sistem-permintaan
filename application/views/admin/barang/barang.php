<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Barang</h5>

                        <form action="<?= base_url('Barang/barang') ?>" method="get" class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-group w-50 mr-2" style="margin-right: 10px;">
                                    <input type="text" class="form-control" name="query" placeholder="Cari barang..." value="<?= $this->input->get('query') ?>">
                                </div>
                                
                                <div class="form-group w-25 mr-2" style="margin-right: 10px;">
                                    <select class="form-control" name="filter">
                                        <option value="">Semua</option>
                                        <option value="nama_barang" <?= ($this->input->get('filter') == 'nama_barang') ? 'selected' : '' ?>>Nama Barang</option>
                                        <option value="kategori_barang" <?= ($this->input->get('filter') == 'kategori_barang') ? 'selected' : '' ?>>Kategori</option>
                                    </select>
                                </div>
                                
                                <div class="form-group w-25">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between mb-3">
                            <a href="<?= base_url('Barang/add') ?>" class="btn btn-primary">Tambah Barang</a>
                        </div>
                        
                        <?php if (empty($barang)): ?>
                            <div class="alert alert-warning" role="alert">
                                Barang tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Foto</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($barang as $b): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php if ($b->foto): ?>
                                                    <img src="<?= base_url('uploads/barang/' . $b->foto) ?>" alt="Foto Barang" width="100">
                                                <?php else: ?>
                                                    <span>Foto tidak tersedia</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= ucfirst($b->nama_barang) ?></td>
                                            <td><?= $b->kategori_barang ?></td>
                                            <td>Rp <?= number_format($b->harga,0,',','.') ?></td>
                                            <td><?= $b->stok ?></td>
                                            <td><?= $b->satuan ?></td>
                                            <td>
                                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalInputStok<?= $b->id_barang ?>">
                                                    <i class="ti ti-plus"></i>
                                                </button>

                                                <a href="<?= base_url('Barang/edit/' . $b->id_barang) ?>" class="btn btn-primary btn-sm"><i class="ti ti-pencil"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="<?= $b->id_barang ?>" data-name="<?= htmlspecialchars($b->nama_barang) ?>">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="modalInputStok<?= $b->id_barang ?>" tabindex="-1" role="dialog" aria-labelledby="modalInputStokLabel<?= $b->id_barang ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalInputStokLabel<?= $b->id_barang ?>">Input Stok - <?= ucfirst($b->nama_barang) ?></h5>
                                                    </div>
                                                    <form action="<?= base_url('Barang/proses_input_stok/' . $b->id_barang) ?>" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <!-- Input Jumlah Stok -->
                                                            <div class="form-group">
                                                                <label for="stok">Jumlah Stok</label>
                                                                <input type="number" class="form-control" id="stok" name="stok" required>
                                                            </div>

                                                            <!-- Input Tanggal -->
                                                            <div class="form-group">
                                                                <label for="tanggal">Tanggal</label>
                                                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                                            </div>

                                                            <!-- Upload Bukti -->
                                                            <div class="form-group">
                                                                <label for="bukti">Upload Bukti</label>
                                                                <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*,.pdf" required>
                                                                <small class="form-text text-muted">Bukti dalam format gambar atau PDF.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <?php endforeach; ?>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Barang "${name}" akan dihapus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `<?= base_url('Barang/barang_delete/') ?>${id}`;
                    }
                });
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("button[data-toggle='modal']").on('click', function() {
            var targetModal = $(this).data('target');
            $(targetModal).modal('show');
        });

        $(".btn-danger[data-dismiss='modal']").click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
