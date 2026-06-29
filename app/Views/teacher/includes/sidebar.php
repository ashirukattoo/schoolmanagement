<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center mb-3">LSS</h4>
    <ul class="list-unstyled">
        <li>
          <a href="<?= base_url('home') ?>" class="d-flex align-items-center">
            <i class="fas fa-home-alt me-2"></i><span>Dashboard</span>
          </a>
        </li>
        <!-- Students -->
        <li>
            <a href="#studentsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-users me-2"></i> <span>Students</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="studentsSubmenu">
                <?php if(strtolower(session()->get('role'))  === 'academic'): ?>
                    <li><a href="<?= base_url('add/students') ?>">Add</a></li>                
                    <li><a href="<?= base_url('students/upload') ?>">Upload</a></li>
                    <li><a href="<?= base_url('view/students'); ?>">View</a></li>
                    <li><a href="<?= base_url('students/assign_route'); ?>">Manage</a></li>
                    <li><a href="#">Reports</a></li>
                <?php else: ?>
                    <li><a href="<?= base_url('view/students'); ?>">View</a></li>
                    <li><a href="#">Reports</a></li>
                <?php endif; ?>
            </ul>
        </li>

        <!-- Teachers -->
        <li>
            <a href="#teachersSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-chalkboard-teacher me-2"></i> <span>Employee</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="teachersSubmenu">
                <li><a href="<?= base_url('employees/add') ?>">Add</a></li>
                <li><a href="<?= base_url('employees/view') ?>">View</a></li>
                <li><a href="<?= base_url('employees/manage') ?>">Manage</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </li>

        <!-- Tours -->
        <li>
            <a href="#classesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-university me-2"></i> <span>Tours</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="classesSubmenu">
                <li><a href="<?= base_url('tours'); ?>">Views</a></li>
                <li><a href="<?= base_url('students/configu') ?>">Allocate students</a></li>
                <li><a href="#">Manage</a></li>
            </ul>
        </li>
        <!-- Transport -->
        <li>
            <a href="#transportSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-bus me-2"></i> <span>Transports</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="transportSubmenu">
                <li><a href="<?= base_url('trans/routes'); ?>"><i class="fas fa-map me-2"></i> Routes</a></li>
                <li><a href="<?= base_url('trans/stations'); ?>"><i class="fas fa-house me-2"></i> Stations</a></li>
                <li><a href="<?= base_url('trans/routes'); ?>"><i class="fas fa-driver me-2"></i> Drivers</a></li>
                <li><a href="<?= base_url('trans/buses'); ?>"><i class="fas fa-track me-2"></i> Vehicles</a></li>
                <li><a href="<?= base_url('trans/routes'); ?>"><i class="fas fa-file me-2"></i> Report</a></li>
            </ul>
        </li>
        <!-- LogOUT -->
        <li>
          <a href="<?= base_url('logout') ?>" class="d-flex align-items-center">
            <i class="fas fa-sign-out-alt me-2"></i><span>Logout</span>
          </a>
        </li>
    </ul>
</div>