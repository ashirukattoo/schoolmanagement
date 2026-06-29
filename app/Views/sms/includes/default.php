<?= $this->include('sms/includes/header') ?>
<?= $this->include('admin/assets/sidebar') ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex bg-info text-white justify-content-between align-items-center shadow-sm">
        <span class="toggle-btn">&#9776;</span>
        <h5 class="m-0"><?= session()->get('role') ?? 'Teacher Dashboard' ?></h5>
        <div id="profile"><span><?= esc(session()->get('user_name')) ?></span></div>
    </nav>

    <!-- Page Content -->
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('sms/includes/footer') ?>