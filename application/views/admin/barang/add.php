<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Barang</h5>
                        <form action="<?= base_url('Barang/simpan') ?>" method="post" enctype="multipart/form-data" id="form_barang">
                            <div class="form-group">
                                <label for="nama_barang">Nama Barang:</label>
                                <input type="text" class="form-control" name="nama_barang" id="nama_barang">
                                <span id="error_nama_barang"></span>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga:</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" name="harga" id="harga">
                                </div>
                                <span id="error_harga"></span>
                            </div>

                            <div class="form-group">
                                <label for="stok">Stok:</label>
                                <input type="number" class="form-control" name="stok" id="stok">
                                <span id="error_stok"></span>
                            </div>

                            <div class="form-group">
                                <label for="kategori_barang">Kategori Barang:</label>
                                <input type="text" class="form-control" name="kategori_barang" id="kategori_barang">
                                <span id="error_kategori_barang"></span>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Satuan Barang:</label>
                                <select name="satuan" id="satuan" class="form-control">
                                    <option value="">--Pilih--</option>
                                    <option value="pcs">pcs</option>
                                    <option value="dus">dus</option>
                                    <option value="box">box</option>
                                </select>
                                <span id="error_satuan"></span>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                <span id="error_keterangan"></span>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Barang</label>
                                <input type="file" name="foto" class="form-control" id="foto">
                                <span id="error_foto"></span>
                            </div>
                            <a href="<?= base_url('Barang/barang') ?>" class="btn btn-danger mt-3">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Barang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#harga').on('input', function () {
        let value = $(this).val().replace(/[^,\d]/g, ''); // Hapus karakter selain angka
        let formattedValue = formatRupiah(value);
        $(this).val(formattedValue);
    });

    function formatRupiah(number) {
        let sisa = number.length % 3;
        let rupiah = number.substr(0, sisa);
        let ribuan = number.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    $('#form_barang').submit(function(e) {
        let isValid = true;

        if ($('#nama_barang').val().trim() === '') {
            $('#error_nama_barang').text('Nama Barang harus diisi').css('color', 'red');
            isValid = false;
        }

        if ($('#harga').val().trim() === '') {
            $('#error_harga').text('Harga harus diisi').css('color', 'red');
            isValid = false;
        } else {
            let rawHarga = $('#harga').val().replace(/\./g, '');
            if (isNaN(rawHarga) || rawHarga <= 0) {
                $('#error_harga').text('Harga harus lebih besar dari 0').css('color', 'red');
                isValid = false;
            }
        }

        if ($('#stok').val().trim() === '' || $('#stok').val() <= 0) {
            $('#error_stok').text('Stok harus lebih besar dari 0').css('color', 'red');
            isValid = false;
        }

        if ($('#kategori_barang').val().trim() === '') {
            $('#error_kategori_barang').text('Kategori Barang harus diisi').css('color', 'red');
            isValid = false;
        }


         // Validate Kepala Departemen (Dropdown)
        var satuan = $('#satuan').val();
        if (satuan === '') {
            $('#error_satuan').text('Pilih Satuan Barang!').css('color', 'red');
            isValid = false;
        }


        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
