<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sistem Perminataan | SUGIN</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="<?= base_url('assets/') ?>img/logo1.png" rel="icon">
  <link href="<?= base_url('assets/') ?>img/logo1.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/') ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/') ?>/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



  

  <!-- Main CSS File -->
  <link href="<?= base_url('assets/') ?>/css/main.css" rel="stylesheet">

    <!-- Vendor JS Files -->
    <script src="<?= base_url('assets/') ?>assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .hero {
    background-size: cover; /* Agar gambar memenuhi seluruh area */
    background-position: center; /* Fokus pada tengah gambar */
    background-repeat: no-repeat; /* Jangan ulang gambar */
    min-height: 100vh; /* Tinggi minimum adalah seluruh viewport */
    display: flex; /* Untuk membuat elemen di dalamnya sejajar */
    align-items: center; /* Pusatkan secara vertikal */
    justify-content: center; /* Pusatkan secara horizontal */
    position: relative; /* Posisi relatif untuk elemen lain */
}

.hero .carousel-container {
    z-index: 2; /* Pastikan konten berada di atas background */
    color: #fff; /* Warna teks putih agar kontras dengan background */
    text-align: center;
    padding: 20px;
}

.hero::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Overlay hitam transparan */
    z-index: 1; /* Layer sebelum konten */
}

.carousel-item {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.carousel-item h2 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 20px;
    animation-duration: 1s; /* Durasi animasi */
}

.carousel-item p {
    font-size: 1.2rem;
    margin-bottom: 20px;
    animation-duration: 1s; /* Durasi animasi */
}

.btn-get-started {
    background-color: #ff5733; /* Warna tombol */
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s;
}

.btn-get-started:hover {
    background-color: #c44127; /* Warna tombol saat hover */
}

    .navmenu .badge {
            display: inline-block;
            position: absolute; 
            top: -5px;  
            right: 0px; 
            background-color:rgb(19, 138, 19); 
            color: white;
            padding: 0.25em 0.4em;
            font-size: 0.8em;
            border-radius: 10px;
        }

        .main{
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

    </style>
  
</head>

<body class="index-page">

<header style="background-color: rgba(42, 44, 57, 0.9) !important;" id="header" class="header d-flex align-items-center fixed-top" >
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="<?= base_url('Frontend');?>" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="<?= base_url('assets/') ?>/img/logo.png" alt=""> -->
        <h1 class="sitename">SUGIN</h1>
      </a>
      <?php
        $this->db->where('status', 'Dijadwalkan HRGA');
        $this->db->where('id_user', $this->session->userdata('id_user'));
        $notif = $this->db->count_all_results('permintaan');
      ?>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="<?= base_url('permintaan') ?>/permintaan" >Formulir Permintaan</a></li>
          <li><a href="<?= base_url('permintaan') ?>/daftar_permintaan" >Daftar Permintaan Saya <?= $notif ? ' <span class="badge badge-primary" style="background-color:green;">'. $notif.'</span>' :''?></a></li>
          <?php if($this->session->userdata('is_active') == 1) : ?>
            <!-- Menu Item Link Profile -->
            <li>
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#profileModal">Profile
                </a>
            </li>
            <li><a href="<?= base_url('auth') ?>/logout" class="active">Logout</a></li>
            <?php endif;?>
          <?php if($this->session->userdata('is_active') == 0) : ?>
            <!-- Menu Item Link Profile -->
            <li><a href="<?= base_url('auth') ?>" class="active">Login</a></li>
            <?php endif;?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <!-- Hero Section -->
    
    <?php (isset($body)) ? $this->load->view($body) : ''; ?>
    

  </main>

    <!-- Modal Profile -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Profil Pengguna</h5>
            </div>
            <div class="modal-body">
                <!-- Form untuk Edit Profil Pengguna -->
                <form action="<?= base_url('Auth/update_profile') ?>" method="POST">
                <div class="mb-3">
                    <label for="nama_user" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_user" name="nama_user" value="<?= $this->session->userdata('nama_user') ?>" required>
                </div>
                <?php
                    $data_departement 	= $this->db->get('departement')->result();
                    $id_departement     = $this->session->userdata('id_departement');
                    $departement        = $this->db->select('nama_departement')->where('id_departement',$id_departement)->get('departement')->result();
                ?>
                <div class="mb-3">
                    <label for="id_departement">Nama Departemen:</label>
                    <select id="id_departement" name="id_departement" class="form-control" required>
                        <option value="">-- Pilih Departemen --</option>
                        <?php foreach ($data_departement as $d): ?>
                            <option value="<?= $d->id_departement ?>" <?= ($id_departement == $d->id_departement) ? 'selected' : '' ;?>>
                                <?= $d->nama_departement ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="error_id_departement"></span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $this->session->userdata('email') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <input type="text" class="form-control" id="level" value="<?= ($this->session->userdata('level') == 1) ? 'Admin Departement' : 'Admin PUD/Purchasing' ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nip" class="form-label">Nip</label>
                    <input type="number" class="form-control" id="nip" name="nip" value="<?= $this->session->userdata('nip') ?>" required>
                </div>

                <!-- Form untuk Ubah Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Ubah Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password baru">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>


  <footer id="footer" class="footer dark-background">
        <div class="container">
            <h3 class="sitename">PT. SUGIURA INDONESIA</h3>
            <p>Kawasan Industri Suryacipta Jl. Surya Utama Kav I-41 Desa Kutanegara, Kecamatan Ciampel Kabupaten Karawang, Jawa Barat 41363 INDONESIA</p>
            <div class="social-links d-flex justify-content-center">
                <a href=""><i class="bi bi-twitter-x"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-skype"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
            <div class="container">
                <div class="copyright">
                    <span>Copyright</span> <strong class="px-1 sitename">SUGIN</strong> <span>All Rights Reserved</span>
                </div>
                
            </div>
        </div>
    </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <script src="<?= base_url('assets/') ?>/vendor/php-email-form/validate.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/aos/aos.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="<?= base_url('assets/') ?>/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="<?= base_url('assets/') ?>/js/main.js"></script>
  <script src="<?= base_url('assets/') ?>alert.js"></script>
  <?php echo "<script>" . $this->session->flashdata('message') . "</script>"; ?>
  <script>
    // Mendapatkan nilai URI segment 3
    window.onload = function() {
        // Mendapatkan segment 3 dari URL
        var segment3 = window.location.pathname.split('/')[3];
        
        // Memeriksa apakah segment3 ada dan cocok dengan ID elemen
        if (segment3) {
            var element = document.getElementById(segment3);
            if (element) {
                // Scroll otomatis ke elemen yang sesuai dengan ID segment3
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };
</script>


</body>

</html>

