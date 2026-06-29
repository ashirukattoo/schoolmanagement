<!DOCTYPE html>
<html>
<head>
    <title>Login - STMS</title>
</head>
<body>
    <h2>Login</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color:red"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/login') ?>" method="post">
        <?= csrf_field() ?>
        <input type="email" name="username" placeholder="Email" value="<?= set_value('username') ?>" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <p>Don’t have an account? <a href="<?= base_url('auth/register') ?>">Register</a></p>
</body>
</html>