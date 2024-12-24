<section class="content">
  <div class="container-fluid">
    <!-- Notifikasi -->
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="card">
          <div class="card-header">Notifikasi</div>
          <div class="card-body">
            Selamat datang <b><?= $this->session->userdata('nama_user') ?></b>, saat ini anda login menggunakan akun <b><?php
            if ($this->session->userdata('level') == 2) {
                echo 'Admin HRGA';
            } else if ($this->session->userdata('level') == 0) {
                echo 'Admin IT / Helper';
            } else {
                echo 'Admin PUD/Purchasing';
            } 
            ?>
            </b>.
          </div>
        </div>
      </section>
    </div>

    <!-- Filter Tahun -->
    <div class="row mb-4">
      <div class="col-md-4">
        <form action="" method="GET">
          <label for="tahun">Pilih Tahun:</label>
          <select name="tahun" id="tahun" class="form-control" onchange="this.form.submit()">
            <?php for ($i = date("Y") - 10; $i <= date("Y"); $i++): ?>
              <option value="<?= $i ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
          </select>
        </form>
      </div>
    </div>


    <!-- Chart Data Barang Masuk dan Keluar per Tahun -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header text-center"><strong>Data Barang Masuk dan Keluar per Tahun</strong></div>
          <div class="card-body">
            
            <!-- Chart Barang Masuk dan Keluar -->
            <p><strong>Total Barang :</strong> <span id="total_keseluruhan">0</span></p>
            <canvas id="barChart" width="400" height="200"></canvas>
            <!-- Tabel untuk Menampilkan Total Barang Masuk, Keluar, dan Keseluruhan -->
            <!-- <table class="table table-bordered mt-2" style="width: 100%;">
              <thead>
                <tr>
                  <th class="text-center" scope="col" style="width: 50%;">Keterangan</th>
                  <th class="text-center" scope="col" style="width: 50%;">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>Total Barang Masuk:</strong></td>
                  <td><span id="totalMasuk">0</span></td>
                </tr>
                <tr>
                  <td><strong>Total Barang Keluar:</strong></td>
                  <td><span id="totalKeluar">0</span></td>
                </tr>
                <tr>
                  <td><strong>Total Keseluruhan:</strong></td>
                  <td><span id="total_keseluruhan">0</span></td>
                </tr>
              </tbody>
            </table> -->
          </div>
        </div>
      </div>
    </div>


    <!-- Chart Jumlah Permintaan Barang per Tahun -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header text-center "><strong>Jumlah Permintaan Barang per Tahun</strong> </div>
          <div class="card-body">
            <p><strong>Total Permintaan Barang:</strong> <span id="totalPermintaan">0</span></p>
            <canvas id="permintaanChart" width="400" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Menyertakan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Ambil data dari PHP untuk tahun yang dipilih
var tahun = '<?= isset($_GET['tahun']) ? $_GET['tahun'] : date("Y") ?>';

// Ambil data dari PHP (pastikan data dalam bentuk array numerik)
var dataBarangMasuk = <?= json_encode($data_barang['barang_masuk']) ?>;
var dataBarangKeluar = <?= json_encode($data_barang['barang_keluar']) ?>;
var dataPermintaanBarang = <?= json_encode($data_permintaan) ?>;

// Pastikan data adalah angka sebelum penjumlahan, jika tidak, ubah menjadi angka
dataBarangMasuk = dataBarangMasuk.map(item => parseFloat(item) || 0);
dataBarangKeluar = dataBarangKeluar.map(item => parseFloat(item) || 0);
dataPermintaanBarang = dataPermintaanBarang.map(item => parseFloat(item) || 0);

// Hitung total barang masuk, keluar, dan permintaan menggunakan reduce
var totalBarangMasuk = dataBarangMasuk.reduce((a, b) => a + b, 0);
var totalBarangKeluar = dataBarangKeluar.reduce((a, b) => a + b, 0);
var totalPermintaan = dataPermintaanBarang.reduce((a, b) => a + b, 0);

// Hitung total keseluruhan barang masuk dan keluar
var total_keseluruhan = totalBarangMasuk + totalBarangKeluar;

// Menampilkan total barang masuk, keluar, permintaan, dan total keseluruhan di elemen HTML
// document.getElementById('totalMasuk').innerText = totalBarangMasuk;
// document.getElementById('totalKeluar').innerText = totalBarangKeluar;
document.getElementById('totalPermintaan').innerText = totalPermintaan;
document.getElementById('total_keseluruhan').innerText = total_keseluruhan;


// Data untuk Chart.js - Data Barang Masuk dan Keluar
var ctx = document.getElementById('barChart').getContext('2d');
var chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [{
      label: 'Barang Masuk',
      data: dataBarangMasuk,
      backgroundColor: 'rgba(0, 123, 255, 0.6)',
      borderColor: 'rgba(0, 123, 255, 1)',
      borderWidth: 1
    }, {
      label: 'Barang Keluar',
      data: dataBarangKeluar,
      backgroundColor: 'rgba(255, 99, 132, 0.6)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Chart untuk Jumlah Permintaan Barang
var ctxPermintaan = document.getElementById('permintaanChart').getContext('2d');
var permintaanChart = new Chart(ctxPermintaan, {
  type: 'line',  // Menggunakan chart garis untuk permintaan
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Bulan
    datasets: [{
      label: 'Jumlah Permintaan Barang',
      data: dataPermintaanBarang, // Data permintaan barang
      fill: false,
      borderColor: 'rgba(54, 162, 235, 1)',
      tension: 0.1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});


</script>
