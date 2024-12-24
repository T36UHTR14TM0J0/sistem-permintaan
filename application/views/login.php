<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Favicons -->
    <link href="<?= base_url('assets/') ?>img/logo1.png" rel="icon">
    <link href="<?= base_url('assets/') ?>img/logo1.png" rel="apple-touch-icon">

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('<?= base_url('assets/img/bg-login.jpg');?>');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            max-width: 100px;
        }

        h3 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group select {
            background-color: #fff;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #007bff;
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #6c757d;
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .forgot-password {
            display: block;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        
        <!-- Logo -->
        <img src="<?= base_url('assets/') ?>img/logo1.png" alt="Logo" class="logo">
        <!-- Form -->
        <p>Masukkan Username dan Password</p>

        <form action="<?= base_url('auth/aksi_login') ?>" method="POST">
            <div class="form-group">
                <label for="nama">Username</label>
                <input type="text" id="nama" name="nama" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="level">Sebagai</label>
                <select name="level" id="level" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Admin Departement</option>
                    <option value="2">Admin HRGA</option>
                    <option value="3">Admin PUD/Purchasing</option>
                    <option value="0">Admin IT / Helper</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Login</button>
            <button type="button" id="btn-registrasi" class="btn btn-secondary">Registrasi</button>
        </form>

        <!-- Link Lupa Password -->
        <a href="javascript:void(0);" id="lupa-password" class="forgot-password mt-3">Lupa Password?</a>
        <p class="mb-0 mt-2 fs-4">Copyright 2024<a href="#" target="_blank" class="pe-1 text-primary text-decoration-underline"> Version 1</a></p>

    </div>

    <!-- Scripts -->
    <script src="<?= base_url(); ?>assets/assets_argon/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/assets_argon/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="<?= base_url(); ?>assets/assets_argon/js/argon-dashboard.min.js?v=1.1.0"></script>
    <script src="<?= base_url('assets/') ?>alert.js"></script>
    <?php echo "<script>" . $this->session->flashdata('message') . "</script>"; ?>

    <script>
        $(document).ready(function(){
            $('#btn-registrasi').on('click',function(){
                window.location.href = "<?= base_url('Auth/registrasi');?>";
            });

            $('#lupa-password').on('click', function() {
            // Ambil nilai username dari input
            const username = $('#nama').val();


            // Buat URL untuk lupa password
            const lupaPasswordUrl = "<?= base_url('auth/lupa_password?username='); ?>" + encodeURIComponent(username);

            // Redirect ke URL tersebut
            window.location.href = lupaPasswordUrl;
        });
        });
    </script>
</body>
</html>
