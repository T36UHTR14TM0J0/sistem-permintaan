<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Data Departemen Head</h5>
                        <form action="<?= base_url('Dept_head/simpan') ?>" method="post" id="add_form">
                            <div class="form-group">
                                <label for="kode">Kode Dept Head:</label>
                                <input type="text" class="form-control" value="<?= $kode; ?>" id="kode" name="kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Dept Head:</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                                <span id="error_nama"></span>
                            </div>
                            <a href="<?= base_url('Dept_head/dept_head') ?>" class="btn btn-danger mt-3">Kembali</a>
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
        // Validate Nama Departemen Head
        $('#nama').on('keyup', function () {
            var nama = $(this).val();
            var regex = /^[A-Za-z\s]+$/; // Only letters and spaces allowed

            if (nama === '') {
                $('#error_nama').text('Nama Dept Head tidak boleh kosong').css('color', 'red');
            } else if (!regex.test(nama)) {
                $('#error_nama').text('Nama Dept Head hanya boleh berisi huruf dan spasi').css('color', 'red');
            } else {
                $('#error_nama').text(''); // Clear error message if valid
            }
        });

        // Form submission validation
        $('#add_form').submit(function (e) {
            var isValid = true;

            // Clear previous error messages
            $('#error_nama').text('');

            // Validate Nama Departemen Head
            var nama = $('#nama').val();
            var regex = /^[A-Za-z\s]+$/;

            if (nama === '') {
                $('#error_nama').text('Nama Dept Head tidak boleh kosong').css('color', 'red');
                isValid = false;
            } else if (!regex.test(nama)) {
                $('#error_nama').text('Nama Dept Head hanya boleh berisi huruf dan spasi').css('color', 'red');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submission if invalid
            }
        });
    });
</script>
