<section id="permintaan" class="section mt-5">
  <div class="container">
    <h2 class="text-center mb-4">Form Permintaan Barang</h2>

    <form method="post" action="<?= base_url('permintaan/simpan'); ?>">
  
      <!-- Nama Peminta -->
      <div class="mb-3">
        <label for="nama_user" class="form-label">Nama Peminta</label>
        <input type="text" class="form-control" name="nama_user" id="nama_user" value="<?= $this->session->userdata('nama_user'); ?>" readonly>
      </div>

      <div class="mb-3">
        <label for="nama_departement" class="form-label">Departemen</label>
        <input type="text" class="form-control" name="nama_departement" id="nama_departement" value="<?= $departement[0]->nama_departement; ?>" readonly>
      </div>

      <!-- Departemen
      <div class="mb-3">
        <label for="id_departement" class="form-label">Departemen</label>
        <select id="id_departement" name="id_departement" class="form-select" required>
          <?php foreach ($departemen as $d): ?>
            <option value="<?= $d->id_departement ?>"><?= $d->nama_departement ?></option>
          <?php endforeach; ?>
        </select>
      </div> -->

      <!-- Barang -->
      <div class="mb-3">
        <label for="barang_id" class="form-label">Barang</label>
        <div class="input-group">
          <select id="barang_id" name="barang_id" class="select2 form-select">
            <option value="" selected disabled>-- Pilih Barang --</option>
            <?php foreach ($kategori_barang as $kategori): ?>
              <optgroup label="<?= $kategori->kategori_barang ?>">
                <?php if (!empty($kategori->barang)): ?>
                  <?php foreach ($kategori->barang as $barang): ?>
                    <option value="<?= $barang->id_barang ?>" 
                            data-nama="<?= $barang->nama_barang ?>" 
                            data-stok="<?= $barang->stok ?>"
                            data-satuan="<?= $barang->satuan ?>"
                            data-kategori="<?= $kategori->kategori_barang ?>">
                      <?= $barang->nama_barang ?> - Stok: <?= $barang->stok.' '. $barang->satuan ?> 
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option disabled>(Barang tidak tersedia)</option>
                <?php endif; ?>
              </optgroup>
            <?php endforeach; ?>
          </select>
          <button style="height: 28px;" type="button" id="add_barang" class="btn btn-success btn-sm">Add</button>
        </div>
      </div>

      <!-- Keterangan -->
      <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea id="keterangan" name="keterangan" class="form-control" rows="3"></textarea>
      </div>

      <!-- Detail Barang List (Keranjang Belanja) -->
      <div class="mb-3">
        <h5>Daftar Barang yang Diminta</h5>
        <div id="barang_list" class="list-group">
          <!-- Item yang ditambahkan akan muncul di sini -->
        </div>
      </div>

      <!-- Hidden Fields to Store Barang IDs and Quantities -->
      <input type="hidden" name="barang_ids[]" id="barang_ids">
      <input type="hidden" name="qtys[]" id="qtys">

      <!-- Tombol Kirim -->
      <button type="submit" class="btn btn-primary">Kirim Permintaan</button>

    </form>
  </div>
</section>

<script>
$(document).ready(function() {
  $('.select2').select2();
  // Fungsi untuk menambah barang ke dalam list (keranjang)
  $('#add_barang').click(function() {
    var barang_id = $('#barang_id').val();
    var barang_nama = $('#barang_id option:selected').data('nama');
    var barang_stok = $('#barang_id option:selected').data('stok');
    var barang_satuan = $('#barang_id option:selected').data('satuan');
    var kategori_barang = $('#barang_id option:selected').data('kategori');

    // Validasi jika barang dipilih
    if (!barang_id || barang_id === "") {
      alert('Pilih barang terlebih dahulu.');
      return;
    }

    // Cek jika barang sudah ada di list, tambahkan jumlahnya
    var existingItem = $('#barang_list').find(`[data-id="${barang_id}"]`);
    if (existingItem.length) {
      // Update jumlah barang yang sudah ada
      var currentQty = existingItem.find('.item-qty').val();
      if (parseInt(currentQty) < parseInt(barang_stok)) {
        existingItem.find('.item-qty').val(parseInt(currentQty) + 1);
      } else {
        alert('Jumlah stok tidak bisa melebihi stok yang tersedia.');
      }
    } else {
      // Jika barang belum ada, tambahkan ke list
      var badgeClass = 'bg-secondary'; // Default color
      var itemHtml = `
        <div class="list-group-item d-flex justify-content-between align-items-center" data-id="${barang_id}" data-stok="${barang_stok}">
            <div>
                <strong>${barang_nama}</strong> - Stok: ${barang_stok}  ${barang_satuan}
                <br>
                <span class="badge ${badgeClass}">${kategori_barang}</span> <!-- Menampilkan kategori dengan warna -->
            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-danger decrease-qty mr-2">-</button>
                <input type="number" class="form-control form-control-sm item-qty" value="1" min="1" style="width: 60px; margin: 0 10px;">
                <button type="button" class="btn btn-sm btn-success increase-qty ml-2">+</button>
                <button type="button" class="btn btn-sm btn-danger remove-item" style="margin-left:10px;"><i class="bi bi-trash"></i></button>
            </div>
        </div>
      `;
      
      // Menambahkan item ke list
      $('#barang_list').append(itemHtml);
    }

    // Reset select dropdown
    $('#barang_id').val('');
  });

  // Fungsi untuk menghapus item dari keranjang
  $('#barang_list').on('click', '.remove-item', function() {
    $(this).closest('.list-group-item').remove();
  });

  // Fungsi untuk menambah jumlah barang
  $('#barang_list').on('click', '.increase-qty', function() {
    var item = $(this).closest('.list-group-item');
    var currentQty = item.find('.item-qty').val();
    var stok = item.data('stok'); // Mengambil stok dari data attribute
    
    if (parseInt(currentQty) < stok) {
      item.find('.item-qty').val(parseInt(currentQty) + 1);
    } else {
      alert('Jumlah stok tidak bisa melebihi stok yang tersedia.');
    }
  });

  // Fungsi untuk mengurangi jumlah barang
  $('#barang_list').on('click', '.decrease-qty', function() {
    var item = $(this).closest('.list-group-item');
    var currentQty = item.find('.item-qty').val();

    if (parseInt(currentQty) > 1) {
      item.find('.item-qty').val(parseInt(currentQty) - 1);
    }
  });

  // Fungsi untuk mengubah jumlah barang secara manual
  $('#barang_list').on('change', '.item-qty', function() {
    var qty = $(this).val();
    var stok = $(this).closest('.list-group-item').data('stok');

    // Pastikan jumlah tidak kurang dari 1 dan tidak melebihi stok
    if (qty < 1) {
      $(this).val(1);
    } else if (qty > stok) {
      alert('Jumlah stok tidak bisa melebihi stok yang tersedia.');
      $(this).val(stok); // Kembalikan ke stok maksimal
    }
  });

  // Sebelum form disubmit, kumpulkan data barang dan qty
  $('form').submit(function() {
    var barangIds = [];
    var qtys = [];
    
    $('#barang_list .list-group-item').each(function() {
      var barangId = $(this).data('id');
      var qty = $(this).find('.item-qty').val();
      
      barangIds.push(barangId);
      qtys.push(qty);
    });

    // Menyimpan barang ids dan qtys ke hidden input fields
    $('#barang_ids').val(barangIds.join(','));
    $('#qtys').val(qtys.join(','));
  });
});

</script>
