<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjadwalan</title>
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
    <h3 style="text-align: center;">Laporan Penjadwalan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminta</th>
                <th>Departemen</th>
                <th>Keterangan</th>
                <th>Tanggal Permintaan</th>
                <th>Tanggal Penjadwalan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Looping Data -->
            <?php $no = 1; ?>
            <?php foreach ($laporan_penjadwalan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $p->nama_user ?></td>
                    <td><?= $p->nama_departement ?></td>
                    <td><?= $p->deskripsi ?></td>
                    <td><?= $p->tanggal_permintaan ? date('d-m-Y', strtotime($p->tanggal_permintaan)) : '-' ?></td>
                    <td><?= $p->tanggal_penjadwalan ? date('d-m-Y', strtotime($p->tanggal_penjadwalan)) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
