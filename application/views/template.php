<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SUGIN</title>
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/') ?>img/logo1.png" />
    <link rel="stylesheet" href="<?= base_url('assets/') ?>assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= base_url('assets/') ?>assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .sidebar-item .badge {
        display: inline-block;
        position: absolute;
        top: -5px;
        right: 0px;
        color: white;
        padding: 0.25em 0.4em;
        font-size: 0.8em;
        border-radius: 10px;
    }

    .badge-warning {
        background-color: rgb(238, 145, 6); /* Default color */
    }

    .badge-info {
        background-color: rgb(33, 150, 243); /* Color for info notification */
    }

    .badge-danger {
        background-color: rgb(244, 67, 54); /* Color for error/critical notification */
    }

    .badge-success {
        background-color: rgb(13, 155, 55); /* Color for error/critical notification */
    }

    #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

    </style>
</head>

<?php
$this->db->where('status', 'Menunggu Diterima');
$notif_permintaan = $this->db->count_all_results('permintaan');
$this->db->where('status', 'Diterima HRGA');
$notif_diterima_hrga = $this->db->count_all_results('permintaan');
$this->db->where('status', 'Diterima PUD/Purchasing');
$notif_diterima_Pud = $this->db->count_all_results('permintaan');

$badge_class_permintaan = ($notif_permintaan) ? 'badge-warning':'';
$badge_class_hrga       = ($notif_diterima_hrga) ? 'badge-info' : '';
$badge_class_pud        = ($notif_diterima_Pud) ? 'badge-success' : '';
?>

<body>
    <div id="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>
    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('Admin');?>" class="text-nowrap logo-img">
                        <img src="<?= base_url('assets/') ?>img/logo1.png" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <!-- Dashboard -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?= base_url('admin') ?>" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Beranda</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" aria-expanded="false">
                            <i class="ti ti-server fs-4"></i>

                                <span class="hide-menu">Data Master</span>
                                <i class="ti ti-chevron-down ms-auto"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="collapse list-unstyled" id="laporanSubmenu">
                                <?php if ($this->session->userdata('level') == 2 || $this->session->userdata('level') == 0) { ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?= base_url('Departement') ?>/departemen" aria-expanded="false">
                                            <span class="hide-menu">Departemen</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('level') == 2 || $this->session->userdata('level') == 0) { ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?= base_url('Dept_head') ?>/dept_head" aria-expanded="false">
                                            <span class="hide-menu">Dept Head</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('level') == 3 || $this->session->userdata('level') == 0) { ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?= base_url('Barang/barang') ?>">
                                            <span class="hide-menu">Barang</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <!-- Logout -->
                                <?php if ($this->session->userdata('level') == 0) { ?>
                                <li class="sidebar-item">
                                    <a href="<?= base_url('Admin/data_user') ?>" class="sidebar-link" aria-expanded="false">
                                        <span class="hide-menu">Data User</span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" aria-expanded="false">
                            <i class="fas fa-tasks fs-4"></i>


                                <span class="hide-menu">Requirement</span>
                                <i class="ti ti-chevron-down ms-auto"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="collapse list-unstyled" id="laporanSubmenu">
                                <!-- Departemen and Approved/Permintaan for certain levels -->
                                <?php if ($this->session->userdata('level') == 2 || $this->session->userdata('level') == 0) { ?>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="<?= base_url('admin') ?>/permintaan" aria-expanded="false">
                                        <span class="hide-menu">Approved / Permintaan</span>
                                        <?= $notif_permintaan ? '<span class="badge ' . $badge_class_permintaan . '">' . $notif_permintaan . '</span>' : '' ?>
                                        
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('level') == 3 || $this->session->userdata('level') == 0) { ?>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="<?= base_url('admin') ?>/pengajuan" aria-expanded="false">
                                        <span class="hide-menu">Approved / Pengajuan</span>
                                        <?= $notif_diterima_hrga ? '<span class="badge ' . $badge_class_hrga . '">' . $notif_diterima_hrga . '</span>' : '' ?>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('level') == 2 || $this->session->userdata('level') == 0) { ?>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="<?= base_url('Penjadwalan') ?>/penjadwalan" aria-expanded="false">
                                        <span class="hide-menu">Penjadwalan</span>
                                        <?= $notif_diterima_Pud ? '<span class="badge ' . $badge_class_pud . '">' . $notif_diterima_Pud . '</span>' : '' ?>
                                    </a>
                                </li>


                                <?php } ?>
                            </ul>
                        </li>


                        <!-- Laporan Dropdown Menu -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" aria-expanded="false">
                                <span><i class="ti ti-cards"></i></span>
                                <span class="hide-menu">Laporan</span>
                                <i class="ti ti-chevron-down ms-auto"></i>
                            </a>
                            <!-- Dropdown Menu -->
                            <ul class="collapse list-unstyled" id="laporanSubmenu">
                                <?php if ($this->session->userdata('level') == 3 || $this->session->userdata('level') == 0) { ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?= base_url('Laporan/laporan_barang') ?>">
                                            <span>Laporan Barang</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('level') == 2 || $this->session->userdata('level') == 0) { ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?= base_url('Laporan/laporan_permintaan') ?>">
                                            <span>Laporan Permintaan</span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="<?= base_url('Laporan/laporan_penjadwalan') ?>">
                                        <span>Laporan Penjadwalan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        
                        <!-- Logout -->
                        <li class="sidebar-item">
                            <a onclick="confirmLogout()" class="sidebar-link" aria-expanded="false">
                                <span><i class="ti ti-login"></i></span>
                                <span class="hide-menu">Keluar</span>
                            </a>
                        </li><br><br><br>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- Sidebar End -->

        <!-- Main Wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <span style="font-size: 16px; font-weight: bolder; margin-right:10px;" class="ms-2" id="username"><?= $this->session->userdata('nama_user'); ?></span>
                                <img src="<?= base_url('assets/') ?>assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            
            <div class="container-fluid">
                <?php $this->load->view($body); ?>
                <div class="py-6 px-6 text-center">
                    <p class="mb-0 fs-4">Copyright 2024<a href="#" target="_blank" class="pe-1 text-primary text-decoration-underline"> Version 1</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/') ?>assets/js/sidebarmenu.js"></script>
    <script src="<?= base_url('assets/') ?>assets/js/app.min.js"></script>
    <script src="<?= base_url('assets/') ?>assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="<?= base_url('assets/') ?>assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="<?= base_url('assets/') ?>alert.js"></script>
    <?php echo "<script>" . $this->session->flashdata('message') . "</script>"; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("auth/logout") ?>';
                }
            });
        }
    </script>
    
</body>

</html>
