<style>
    .is-valid {
        border-color: #28a745; /* Hijau untuk valid */
        background-color: #d4edda; /* Hijau muda untuk valid */
    }
    .is-invalid {
        border-color: #dc3545; /* Merah untuk invalid */
        background-color: #f8d7da; /* Merah muda untuk invalid */
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
    }
    .valid-feedback {
        color: #28a745;
        font-size: 0.875em;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data User</h5>

                        <!-- Form Filter -->
                        <form action="<?= base_url('Admin/data_user') ?>" method="get" class="mb-3">
                            <div class="d-flex left-content-between mb-3">
                                <div class="form-group col-md-4 mr-2" style="margin-right: 10px;">
                                    <input type="text" class="form-control" name="query" placeholder="Cari Nama User ..." value="<?= $this->input->get('query') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-info w-100">Filter</button>
                                </div>
                            </div>
                        </form>

                        <!-- Button to open Add User Modal -->
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal" data-action="add">Tambah User</button>
                        </div>

                        <?php if (empty($users)): ?>
                            <div class="alert alert-warning" role="alert">
                                Data user tidak ditemukan.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nip</th>
                                            <th>Nama User</th>
                                            <th>Nama Departement</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Level</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($users as $u): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $u->nip ?></td>
                                            <td><?= ucfirst($u->nama_user) ?></td>
                                            <td><?= $u->nama_departement ?></td>
                                            <td><?= $u->email ?></td>
                                            <td><?= $u->password ?></td>
                                            <td><?= get_level_name($u->level); ?></td>
                                            <td>
                                                <?php if($u->is_active != 1 && $this->session->userdata('level') == 0) : ?>
                                                    <button type="button" class="btn btn-success btn-sm btn-validasi" data-id="<?= $u->id_user ?>">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <!-- Button to open Edit User Modal -->
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#userModal" data-action="edit" data-id="<?= $u->id_user ?>" data-nip="<?= $u->nip ?>" data-nama_user="<?= $u->nama_user ?>" data-email="<?= $u->email ?>" data-password="<?= $u->password ?>" data-level="<?= $u->level ?>" data-id_departement="<?= $u->id_departement ?>"><i class="ti ti-pencil"></i></button>

                                                <!-- Delete Button with confirmation -->
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="<?= $u->id_user ?>" data-name="<?= htmlspecialchars($u->nama_user) ?>"><i class="ti ti-x"></i></a>
                                            </td>
                                        </tr>
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

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah User</h5>
            </div>
            <form id="userForm" action="<?= base_url('Admin/user_add') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control validasi" id="nip" name="nip">
                    </div>
                    <div class="form-group">
                        <label for="nama_user">Nama User</label>
                        <input type="text" class="form-control validasi" id="nama_user" name="nama_user">
                    </div>
                    <div class="form-group">
                        <label for="id_departement">Departemen</label>
                        <select name="id_departement" class="form-control validasi" id="id_departement">
                            <option value="">-- Pilih Departemen --</option>
                            <?php foreach ($departement as $d): ?>
                                <option value="<?= $d->id_departement ?>"><?= $d->nama_departement ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control validasi" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control validasi" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" class="form-control validasi" id="level">
                            <option value="">-- Pilih --</option>
                            <option value="1">Admin Departement</option>
                            <option value="2">Admin HRGA</option>
                            <option value="3">Admin PUD/Purchasing</option>
                            <option value="0">Admin IT / Helper</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-batal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        // Konfirmasi Hapus User
        $('.btn-delete').on('click', function (e) {
            e.preventDefault();

            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `User "${name}" akan dihapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= base_url('Admin/user_delete/') ?>${id}`;
                }
            });
        });

        // Menangani tampilan modal dan mempersiapkan form untuk add atau edit
        $('[data-toggle="modal"]').on('click', function (event) {
            var button = $(this);  // Tombol yang memicu modal
            var action = button.data('action');  // Tindakan (add/edit)

            var modal = $('#userModal');
            var form = modal.find('#userForm');
            var modalTitle = modal.find('.modal-title');

            // Reset form dan set action berdasarkan tindakan (add atau edit)
            if (action === 'add') {
                form.attr('action', '<?= base_url('Admin/user_add') ?>');
                modalTitle.text('Tambah User');
                form[0].reset();  // Reset form untuk tambah user
            } else if (action === 'edit') {
                var id = button.data('id');
                var nip = button.data('nip');
                var nama_user = button.data('nama_user');
                var email = button.data('email');
                var password = button.data('password');
                var level = button.data('level');
                const departement_id = button.data('id_departement');
                form.attr('action', '<?= base_url('Admin/user_edit/') ?>' + id);
                modalTitle.text('Edit User - ' + nama_user);

                modal.find('#nip').val(nip);
                modal.find('#nama_user').val(nama_user);
                modal.find('#email').val(email);
                modal.find('#password').val(password);
                modal.find('#level').val(level);
                $('#id_departement').val(departement_id); 
            }

            // Menampilkan modal secara manual menggunakan jQuery
            modal.modal('show');
        });

        // Validasi form dan submit
        $('#userForm').submit(function (event) {
            event.preventDefault();  // Mencegah form submit langsung

            let valid = true;

            $(".validasi").each(function () {
                const input = $(this);
                const feedback = input.next(".invalid-feedback, .valid-feedback");

                if (input.val() === "") {
                    input.addClass("is-invalid").removeClass("is-valid");
                    feedback.remove();
                    input.after('<div class="invalid-feedback">Field ini harus diisi</div>');
                    valid = false;
                } else {
                    input.addClass("is-valid").removeClass("is-invalid");
                    feedback.remove();
                    input.after('<div class="valid-feedback">Tampilan sudah benar!</div>');
                }
            });

            if (valid) {
                this.submit();  // Submit form jika valid
            }
        });

        // Hapus feedback saat input berubah
        $(".validasi").on("input", function () {
            $(this).removeClass("is-invalid is-valid");
            $(this).next(".invalid-feedback, .valid-feedback").remove();
        });

        // Tambahkan event handler untuk tombol Batal
        $('#btn-batal').on('click', function() {
            $('#userModal').modal('hide');  // Menutup modal secara manual
        });

        $(document).on('click', '.btn-validasi', function () {
            const id = $(this).data('id'); // Ambil ID user dari atribut data-id

            // Konfirmasi validasi dengan SweetAlert
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data user ini akan divalidasi!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, validasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim AJAX request ke controller
                    $.ajax({
                        url: '<?= base_url("Admin/validate_user") ?>', // Endpoint controller
                        type: 'POST',
                        data: { id_user: id }, // Sesuaikan parameter POST
                        dataType: 'json', // Pastikan respon berupa JSON
                        success: function (response) {
                            // Jika server mengembalikan respon sukses
                            if (response.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message || 'User telah divalidasi.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload(); // Refresh halaman jika diperlukan
                                });
                            } else {
                                // Jika server mengembalikan respon gagal
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message || 'Terjadi kesalahan.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            // Penanganan jika terjadi error dari server atau jaringan
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Tidak dapat menghubungi server.'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });


    });
</script>
