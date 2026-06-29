<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <ul class="list-unstyled">
        <div class="sidebar-header d-flex flex-column align-items-center justify-content-center py-4">
            <div class="logo-container mb-2">
                <img class="sidebar-logo" src="<?= base_url('images/lss_logo.png') ?>" alt='LSS'> 
            </div>
           <span class="sidebar-title mt-1 d-none d-lg-inline">LSSMS</span>
        </div>
        <li>
          <a href="<?= base_url('admin/dashboard') ?>" class="d-flex align-items-center">
            <i class="fas fa-home-alt me-2"></i><span>Dashboard</span>
          </a>
        </li>
        <!-- Students -->
        <li>
            <a href="#studentsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-users me-2"></i> <span>Students</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="studentsSubmenu">
                <li><a href="<?= base_url('admin/add/students') ?>">Add</a></li>                
                <li><a href="<?= base_url('admin/view/students') ?>">View</a></li>
                <li><a href="<?= base_url('admin/manage/students'); ?>">Manage</a></li>
                <li><a href="<?= base_url('admin/reports/students'); ?>">Reports</a></li>
            </ul>
        </li>
        <!-- Teachers -->
        <li>
            <a href="#teachersSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-chalkboard-teacher me-2"></i> <span>Employee</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="teachersSubmenu">
                <li><a href="<?= base_url('admin/add/employees') ?>">Add</a></li>
                <li><a href="<?= base_url('admin/view/employees') ?>">View</a></li>
                <li><a href="<?= base_url('admin/manage/employees') ?>">Manage</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </li>

        <!-- Subjects -->
        <li>
            <a href="#subjects" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-book me-2"></i> <span>Subjects</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="subjects">
                <li><a href="<?= base_url('admin/add/subject') ?>">Add</a></li>
                <li><a href="<?= base_url('admin/view/subjects') ?>">View</a></li>
                <li><a href="<?= base_url('admin/manage/subjects') ?>">Manage</a></li>
                <li><a href="<?= base_url('/admin/assign/subjects/employees') ?>">Teachers</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </li>

        <!-- Tours -->
        <li>
            <a href="#classesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-university me-2"></i> <span>Classes</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="classesSubmenu">
                <li><a href="<?= base_url('admin/add/stream'); ?>"><i class="fas fa-add me-2"></i> Add Stream</a></li>
                <li><a href="<?= base_url('admin/view/stream'); ?>"><i class="fas fa-eye me-2"></i> View Streams</a></li>
                <li><a href="<?= base_url('admin/add/class'); ?>"><i class="fas fa-house me-2"></i> Add Class</a></li>
                <li><a href="<?= base_url('admin/view/class'); ?>"><i class="fas fa-bank me-2"></i> View Class</a></li>
                <li><a href="<?= base_url('admin/associate/subject') ?>"><i class="fas fa-book me-2"></i> Associate Subjects</a></li>
                <li><a href="<?= base_url('admin/report/stream') ?>"><i class="fas fa-file-pdf me-2"></i> Reports</a></li>
            </ul>
        </li>
        <!-- Examination -->
        <li>
            <a href="#ExaminationSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-file-excel me-2"></i> <span>Examination</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="ExaminationSubmenu">
                <li><a href="<?= base_url('admin/setting/exams'); ?>"><i class="fas fa-map me-2"></i> Setting</a></li>
                <li><a href="<?= base_url('admin/view/exams'); ?>"><i class="fas fa-eye me-2"></i> View</a></li>
                <li><a href="<?= base_url('admin/record/exams'); ?>"><i class="fas fa-pencil me-2"></i>Record</a></li>
                <li><a href="<?= base_url('trans/routes'); ?>"><i class="fas fa-envelope me-2"></i> Notification</a></li>
            </ul>
        </li>
        <!-- Transport -->
        <li>
            <a href="#transportSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="d-flex align-items-center">
                <i class="fas fa-bus me-2"></i> <span>Transport</span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="transportSubmenu">
                <li><a href="<?= base_url('admin/trans/routes'); ?>"><i class="fas fa-map me-2"></i> Routes</a></li>
                <li><a href="<?= base_url('admin/trans/tours'); ?>"><i class="fas fa-map me-2"></i> Tours</a></li>
                <li><a href="<?= base_url('admin/trans/stations'); ?>"><i class="fas fa-map me-2"></i> Stations</a></li>
                <li><a href="<?= base_url('admin/trans/stations/students'); ?>"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="<?= base_url('admin/trans/buses'); ?>"><i class="fas fa-bus me-2"></i> Vehicles</a></li>
                <li><a href="<?= base_url('trans/buses'); ?>"><i class="fas fa-file-alt me-2"></i> Report</a></li>
                <li><a href="<?= base_url('trans/routes'); ?>"><i class="fas fa-envelope me-2"></i> Notification</a></li>
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