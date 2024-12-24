<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .header img {
            width: 150px;
            position: relative;
            bottom: -35px;
        }
        .header h1 {
            font-size: 16px;
            position: relative;
            top: -5px;
            margin: 0;
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
            text-align: right;
            font-size: 12px;
            margin: 0;
        }
        .footer table {
            width: 150px;
            border: none;
            border-collapse: collapse;
        }
        .footer td, .footer th {
            border: none;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <?php
        $image_path = base_url('assets/img/logo1.png');
        $image_data = base64_encode(file_get_contents($image_path));
        ?>
        <img src="data:image/png;base64,<?= $image_data ?>" alt="Logo">
        <h1>PT. SUGIURA INDONESIA</h1>
        <p>Kawasan Industri Suryacipta Jl. Surya Utama Kav I-41</p>
        <p>Desa Kutanegara, Kecamatan Ciampel, Kota Karawang, Jawa Barat 41363 INDONESIA</p>
    </div>
    <h3 style="text-align: center;">Laporan Barang</h3>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Nama Barang</th>
                <th style="text-align: center;">Kategori</th>
                <th style="text-align: center;">Jumlah</th>
                <!-- <th>Nama User</th> -->
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_barang_keluar = 0; 
            $total_barang_masuk = 0; 
            $no = 1; 
            foreach ($laporan_barang as $b): 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $b->tanggal ? date('d-m-Y', strtotime($b->tanggal)) : '-' ?></td>
                <td><?= ucfirst($b->nama_barang) ?></td>
                <td><?= $b->kategori ?></td>
                <td><?= $b->jumlah . ' '. $b->satuan ?></td>
                <!-- <td><?= $b->user_name ?></td> -->
                <td>
                <?php 
                    if ($b->status == 'masuk') {
                    echo '<span style="color: white; background-color: green; padding: 2px 5px;">' . $b->status . '</span>';
                    } elseif ($b->status == 'keluar') {
                    echo '<span style="color: white; background-color: red; padding: 2px 5px;">' . $b->status . '</span>';
                    } else {
                    echo $b->status; // Untuk status lainnya
                    }
                ?>
                </td>

            </tr>
            <?php 
            if ($b->status == 'masuk') {
                $total_barang_masuk += $b->jumlah;
            } elseif ($b->status == 'keluar') {
                $total_barang_keluar += $b->jumlah;
            }
            endforeach; 
            ?>
            <tr>
                <th colspan="5" style="text-align: center;">Jumlah Barang Masuk</th>
                <td><?= $total_barang_masuk; ?></td>
            </tr>
            <tr>
                <th colspan="5" style="text-align: center;">Jumlah Barang Keluar</th>
                <td><?= $total_barang_keluar; ?></td>
            </tr>
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
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
            <td><?= $nama_user; ?></td>
        </tr>
    </table>

    </div>
</body>
</html>
