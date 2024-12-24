<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Barang</h5>
                        <form action="<?= base_url('Barang/barang_update/' . $barang->id_barang) ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="foto_lama" value="<?= $barang->foto ?>">

                            <div class="form-group">
                                <label for="nama_barang">Nama Barang:</label>
                                <input type="text" class="form-control" name="nama_barang" value="<?= $barang->nama_barang ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="harga">Harga:</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="harga" class="form-control" name="harga" value="<?= number_format($barang->harga, 0, ',', '.') ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="stok">Stok:</label>
                                <input type="number" class="form-control" name="stok" value="<?= $barang->stok ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="kategori_barang">Kategori Barang:</label>
                                <input type="text" class="form-control" name="kategori_barang" value="<?= $barang->kategori_barang ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <textarea class="form-control" name="keterangan" required><?= $barang->keterangan ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Barang</label>
                                <input type="file" name="foto" class="form-control" id="foto">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Barang</button>
                            <a href="<?= base_url('Barang/barang') ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        // Format default nilai harga
        let defaultHarga = $('#harga').val().replace(/[^,\d]/g, '');
        $('#harga').val(formatRupiah(defaultHarga));

        // Format saat pengguna mengetik
        $('#harga').on('input', function () {
            let value = $(this).val().replace(/[^,\d]/g, '');
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
    });
</script>
