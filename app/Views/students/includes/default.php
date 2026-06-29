<?= $this->include('templates/header') ?>
<?= $this->include('templates/sidebar') ?>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex bg-secondary text-white justify-content-between align-items-center shadow-sm">
        <span class="toggle-btn">&#9776;</span>
        <h5 class="m-0">Admin Dashboard</h5>
        <div id="profile"><span>Welcome, <?= esc(session()->get('user_name')) ?></span></div>
    </nav>

    <!-- Page Content -->
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('templates/footer') ?>