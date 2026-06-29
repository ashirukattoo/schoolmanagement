<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LSS | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Local CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link href="<?= base_url('assets/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/datatables/buttons.bootstrap4.min.css') ?>">
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            background-image: url(<?= base_url('images/background.jpeg') ?>);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
        }
        .login-container {
            max-width: 450px;
            margin: 80px auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .login-header h2 {
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .login-header p {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        footer {
            text-align: center;
            padding: 1.5rem 0;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header text-center">
        <?php
        $hour = date('H');
        if($hour < 12){
            $greeting = "Good Morning!";
        } elseif($hour < 18){
            $greeting = "Good Afternoon!";
        } else {
            $greeting = "Good Evening!";
        }
        ?>
        <h4 class="alert alert-info">LSS MANAGEMENT SYSTEM</h4>
    </div>

    <form action="<?= site_url('attemptLogin') ?>" method="post">
        <?php if (session()->getFlashdata('error')) { ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php }elseif (session()->getFlashdata('success')) { ?>
            <div class="alert alert-success">
                <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php } ?>
        <div class="mb-3">
            <label for="username" class="form-label">Username or Email</label>
            <input type="text" class="form-control" id="username" name="email" placeholder="Enter username or email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-info w-100 text-center">Login</button>
    </form>

    <div class="mt-3 text-center">
        <P>You don't have Account?<a href="<?= base_url('register') ?>"> Register</a>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> LSS. All rights reserved.
</footer>

    <!-- Local JS (Bootstrap & jQuery) -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
