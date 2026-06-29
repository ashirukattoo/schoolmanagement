<?= $this->include('admin/assets/header') ?>
<?= $this->include('admin/assets/sidebar') ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex bg-info text-white justify-content-between align-items-center shadow-sm">
        <span class="toggle-btn">&#9776;</span>
        <h5 class="m-0">Admin Dashboard</h5>
        <div id="profile"><span class="span text-white">Welcome: <?= esc(session()->get('user_name')) ?></span></div>
    </nav>

    <!-- Page Content -->
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('admin/assets/footer') ?>