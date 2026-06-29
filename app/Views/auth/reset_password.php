<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LSS | Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Local CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">

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
        <h4 class="alert alert-info">LSS MANAGEMENT SYSTEM</h4>
        <p>Set Your New Password</p>
    </div>

    <form action="" method="post">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <input type="hidden" name="token" value="<?= esc($token) ?>">

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn btn-info w-100">Set Password</button>
    </form>

    <div class="mt-3 text-center">
        <a href="<?= base_url('login') ?>">Back to Login</a>
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
