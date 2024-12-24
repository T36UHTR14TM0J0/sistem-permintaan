<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Update Data Departement Head</h5>
                        <form action="<?= base_url('Dept_head/update/' . $data->id_dept_head) ?>" method="post" id="edit_form">
                            <div class="form-group">
                                <label for="kode">Kode Dept Head:</label>
                                <input type="text" class="form-control" value="<?= $data->id_dept_head;?>" id="kode" name="kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Dept Head:</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= $data->nama_dept_head ?>">
                                <span id="error_nama"></span>
                            </div>
                            <a href="<?= base_url('Dept_head/dept_head') ?>" class="btn btn-danger mt-3">Kembali</a>
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        // Validate Nama Departemen Head
        $('#nama').on('keyup', function() {
            var nama = $(this).val();
            var regex = /^[A-Za-z\s]+$/; // Only letters and spaces allowed

            if (nama === '') {
                $('#error_nama').text('Nama Dept Head tidak boleh kosong').css('color', 'red');
            } 
            
            if (!regex.test(nama)) {
                $('#error_nama').text('Nama Dept Head hanya boleh berisi huruf dan spasi').css('color', 'red');
            } 
        });

        // Form submission validation
        $('#edit_form').submit(function(e) {
            var isValid = true;

            // Clear previous error messages
            $('#error_nama').text('');

            // Validate Nama Departemen Head
            var nama = $('#nama').val();
            var regex = /^[A-Za-z\s]+$/;

            if (nama === '') {
                $('#error_nama').text('Nama Dept Head tidak boleh kosong').css('color', 'red');
                isValid = false;
            }
            
            if (!regex.test(nama)) {
                $('#error_nama').text('Nama Dept Head hanya boleh berisi huruf dan spasi').css('color', 'red');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Prevent form submission if invalid
            }
        });
    });
</script>
