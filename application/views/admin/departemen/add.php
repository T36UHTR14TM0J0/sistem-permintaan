<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data Departemen</h5>
                        <form action="<?= base_url('Departement/simpan') ?>" method="post" id="departemenForm">
                            <div class="form-group">
                                <label for="kode">Kode Departemen:</label>
                                <input type="text" class="form-control" value="<?= $kode;?>" id="kode" name="kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Departemen:</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                                <span id="error_nama"></span>
                            </div>
                            <div class="form-group">
                                <label for="id_dept_head">Nama Kepala Departemen:</label>
                                <select class="form-control" id="id_dept_head" name="id_dept_head">
                                    <option value="">-- Pilih Kepala Departemen --</option>
                                    <?php foreach ($dept_heads as $head): ?>
                                        <option value="<?= $head->id_dept_head ?>">
                                            <?= $head->nama_dept_head ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="error_id_dept_head"></span>
                            </div>
                            <a href="<?= base_url('Departement/departemen') ?>" class="btn btn-danger mt-3">Kembali</a>
                            <button type="submit" class="btn btn-primary mt-3">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $('#loading-overlay').fadeOut();
        // Validate Nama Departemen
        $('#nama').on('keyup', function () {
            var nama = $(this).val();
            var regex = /^[A-Za-z\s\-&]+$/; // Letters, spaces, hyphens (-), and ampersands (&) allowed

            if (nama === '') {
                $('#error_nama').text('Nama Departement tidak boleh kosong').css('color', 'red');
            } else if (!regex.test(nama)) {
                $('#error_nama').text('Nama Departement hanya boleh berisi huruf, spasi, tanda hubung (-), dan &').css('color', 'red');
            } else {
                $('#error_nama').text(''); // Clear error if valid
            }
        });

        // Form submission validation
        $('#add_form').submit(function (e) {
            var isValid = true;

            // Clear previous error messages
            $('#error_nama').text('');
            $('#error_id_dept_head').text('');

            // Validate Nama Departemen
            var nama = $('#nama').val();
            var regex = /^[A-Za-z\s\-&]+$/; // Letters, spaces, hyphens (-), and ampersands (&) allowed

            if (nama === '') {
                $('#error_nama').text('Nama Departement tidak boleh kosong').css('color', 'red');
                isValid = false;
            } else if (!regex.test(nama)) {
                $('#error_nama').text('Nama Departement hanya boleh berisi huruf, spasi, tanda hubung (-), dan &').css('color', 'red');
                isValid = false;
            }

            // Validate Kepala Departemen (Dropdown)
            var idDeptHead = $('#id_dept_head').val();
            if (idDeptHead === '') {
                $('#error_id_dept_head').text('Pilih Kepala Departemen!').css('color', 'red');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submission if invalid
            }
        });
    });

</script>