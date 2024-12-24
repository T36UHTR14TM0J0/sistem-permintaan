<section id="daftar-permintaan" class="section">
      <div class="container">
        <h2 class="text-center">Detail</h2>
        <table  class="table table-bordered">
            <tr>
              <th>Tanggal Permintaan</th>
              <td><?= date('d-m-Y', strtotime($permintaan->tanggal_permintaan)); ?></td>
            </tr>
            <tr>
              <th>Nama Peminta</th>
              <td><?=$permintaan->nama_user; ?></td>
            </tr>
            <tr>
              <th>Departement</th>
              <td><?=$permintaan->nama_departement; ?></td>
            </tr>
            <tr>
              <th>Keterangan</th>
              <td><?=$permintaan->deskripsi; ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?=$permintaan->status; ?></td>
            </tr>
        </table>
        <h4>Daftar Barang Yang Diiminta :</h4>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Barang</th>
              <th class="text-center">Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; foreach($barang as $b) : ?>
              <tr>
                <td class="text-center"><?=$no++;?></td>
                <td><?=$b->nama_barang;?></td>
                <td class="text-center"><?=$b->jumlah;?></td>
              </tr>
            <?php endforeach;?>

          </tbody>
        </table>
      </div>
</section>