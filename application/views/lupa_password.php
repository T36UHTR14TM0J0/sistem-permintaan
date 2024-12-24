<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>

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

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo -->
        <img src="<?= base_url('assets/') ?>img/logo1.png" alt="Logo" class="logo">
        
        <!-- Form Lupa Password -->
        <h3>Lupa Password</h3>
        <p>Masukkan email Anda untuk mengatur ulang password.</p>

        <form action="<?= base_url('auth/aksi_lupa_password') ?>" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Anda" value="<?= $email;?>" required>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Kirim Link Reset Password</button>
            <button type="button" id="btn-login" class="btn btn-secondary">Kembali ke Login</button>
        </form>
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
            // Tombol Kembali ke Login
            $('#btn-login').on('click',function(){
                window.location.href = "<?= base_url('Auth');?>"; // Mengarahkan ke halaman login
            });
        });
    </script>
</body>
</html>
