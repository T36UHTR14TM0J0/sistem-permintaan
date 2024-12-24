<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Permintaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative; /* Membuat elemen dalam header dapat diposisikan relatif */
        }
        .header img {
            width: 150px;
            position: relative;
            bottom: -35px; /* Menggeser logo lebih dekat ke teks heading */
        }
        .header h1 {
            font-size: 16px;
            position: relative;
            top: -5px; /* Menggeser teks heading lebih dekat ke logo */
            margin: 0; /* Menghapus margin default */
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right; /* Teks rata kanan */
            font-size: 12px; /* Ukuran font lebih kecil */
            margin: 0; /* Menghapus margin bawaan */
        }
        .footer table {
            width: 150px;
            border: 0px; /* Menghapus border tabel utama */
            border-collapse: collapse; /* Menghindari garis */
        }
        .footer td, .footer th {
            border: none; /* Menghapus border pada elemen tabel */
            padding: 5px; /* Padding untuk estetika */
            text-align: center; /* Teks rata tengah */
        }
    </style>
</head>
<body>
    <div class="header">
        <?php
        // Mendapatkan gambar sebagai Base64
        $image_path = base_url('assets/img/logo1.png');
        $image_data = base64_encode(file_get_contents($image_path));
        ?>
        <!-- Gambar dengan Base64 Encoding -->
        <img src="data:image/png;base64,<?= $image_data ?>" alt="Logo">
        <h1>PT. SUGIURA INDONESIA</h1>
        <p>Kawasan Industri Suryacipta Jl. Surya Utama Kav I-41</p>
        <p>Desa Kutanegara, Kecamatan Ciampel, Kota Karawang, Jawa Barat 41363 INDONESIA</p>
    </div>
    <h3 style="text-align: center;">Laporan Permintaan</h3>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Nama User</th>
                <th style="text-align: center;">Departement</th>
                <th style="text-align: center;">Barang</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Looping Data -->
            <?php $no = 1;$total_permintaan = count($laporan_permintaan); ?>
            <?php foreach ($laporan_permintaan as $p): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($p->tanggal_permintaan)) ?></td>
                    <td><?= htmlspecialchars($p->nama_user) ?></td>
                    <td><?= htmlspecialchars($p->nama_departement) ?></td>
                    <td>
                        <?php
                        $barang_list = $this->m_laporan->get_barang_by_permintaan($p->id_permintaan);
                        if (!empty($barang_list)) {
                            foreach ($barang_list as $barang) {
                                echo htmlspecialchars($barang->nama_barang) . ' (' . $barang->jumlah . ' ' . $barang->satuan . ')<br>';
                            }
                        } else {
                            echo 'Tidak ada barang';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($p->status == 'Menunggu Diterima'): ?>
                            <span class="badge bg-warning text-dark">Permintaan</span>
                        <?php elseif ($p->status == 'Diterima HRGA'): ?>
                            <span class="badge bg-warning">Menunggu PUD/Purchasing</span>
                        <?php elseif ($p->status == 'Diterima PUD/Purchasing'): ?>
                            <span class="badge bg-success"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Menunggu Pud/Purchasing'): ?>
                            <span class="badge bg-warning"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Ditolak HRGA'): ?>
                            <span class="badge bg-danger"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Ditolak PUD/Purchasing'): ?>
                            <span class="badge bg-danger"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Dijadwalkan HRGA'): ?>
                            <span class="badge bg-success"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Sudah Diterima Departement'): ?>
                            <span class="badge bg-success"><?= $p->status ?></span>
                        <?php elseif ($p->status == 'Batas'): ?>
                            <span class="badge bg-danger">Permintaan melebihi batas 30 hari</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th style="text-align: center;" colspan="5">Total Permintaan</th>
                <th style="text-align: center;"><?= $total_permintaan ?></th>
            </tr>
        </tbody>
    </table>
    <!-- <h4 style="text-align: left; margin-top: 20px;">Total Permintaan: <?= $total_permintaan ?></h4> -->
    <div class="footer">
        <table align="right">
            <tr>
                <td>Karawang,<?= date('d-m-Y'); ?></td> <!-- Menampilkan tanggal sekarang -->
            </tr>
            <tr>
                <td><b><?= get_level_name($this->session->userdata('level')) ?></b></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><?= $nama_user; ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
