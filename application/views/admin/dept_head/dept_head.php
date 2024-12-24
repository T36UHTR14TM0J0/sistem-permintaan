<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Departement Head</h5>

                        <!-- Form Filter -->
                        <form action="<?= base_url('Dept_head/dept_head') ?>" method="get" class="mb-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="query" placeholder="Cari kode atau nama ..." value="<?= $this->input->get('query') ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>
                            </div>
                        </form>

                        <!-- Button Tambah Departemen -->
                        <div class="mb-3">
                            <a href="<?= base_url('Dept_head/add') ?>" class="btn btn-primary">Tambah</a>
                        </div>

                        <!-- Tampilkan Pesan Jika Tidak Ada Departemen -->
                        <?php if (empty($data)): ?>
                            <div class="alert alert-warning" role="alert">
                                Data tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Dept Head</th>
                                            <th>Nama Dept Head</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($data as $d): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= ucfirst($d->id_dept_head) ?></td>
                                            <td><?= ucfirst($d->nama_dept_head) ?></td>
                                            <td>
                                                <a href="<?= base_url('Dept_head/edit/' . $d->id_dept_head) ?>" class="btn btn-primary btn-sm"><i class="ti ti-pencil"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="<?= $d->id_dept_head ?>" data-name="<?= htmlspecialchars($d->nama_dept_head) ?>">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <!-- Pagination -->
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
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data departement head "${name}" akan dihapus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true // Membalikkan posisi tombol
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `<?= base_url('Dept_head/delete/') ?>${id}`;
                    }
                });

            });
        });
    });
</script>
