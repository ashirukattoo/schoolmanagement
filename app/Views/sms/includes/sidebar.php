<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center py-3">
        <img style="height: 50px; width: 50px;" src="<?= base_url('images/lss_logo.png') ?>" alt="LSS">
        <span class="sidebar-title ms-2 d-none d-lg-inline">LSSMS</span>
    </div>

    <ul class="list-unstyled sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="<?= base_url('admin/dashboard') ?>" class="d-flex align-items-center">
                <i class="fas fa-home-alt me-2"></i><span class="menu-text">Dashboard</span>
            </a>
        </li>
        <!-- Students -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-users me-2"></i><span class="menu-text">Students</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <li><a href="<?= base_url('admin/add/students') ?>">Add</a></li>
                <li><a href="<?= base_url('admin/view/students') ?>">View</a></li>
                <li><a href="<?= base_url('admin/manage/students') ?>">Manage</a></li>
                <li><a href="<?= base_url('admin/reports/students') ?>">Reports</a></li>
            </ul>
        </li>
        <!-- Employees / HR -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-chalkboard-teacher me-2"></i><span class="menu-text">Human Resources</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <!-- Employee Nested -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">Employee <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/add/employees') ?>">Add Employee</a></li>
                        <li><a href="<?= base_url('admin/view/employees') ?>">View Employees</a></li>
                        <li><a href="<?= base_url('admin/manage/employees') ?>">Manage Employees</a></li>
                    </ul>
                </li>
                <!-- Payroll Nested -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">Payroll <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/salary-grades') ?>">Salary Grade</a></li>
                        <li><a href="<?= base_url('admin/payroll/setup') ?>">Payroll Setup</a></li>
                        <li><a href="<?= base_url('admin/payroll/list') ?>">Payroll List</a></li>
                        <li><a href="<?= base_url('admin/payroll/reports') ?>">Reports</a></li>
                    </ul>
                </li>
                <!-- Recruitment Nested -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">Recruitment <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/recruitment/jobs') ?>">Job Posts</a></li>
                        <li><a href="<?= base_url('admin/recruitment/applicants') ?>">Applicants</a></li>
                        <li><a href="<?= base_url('admin/recruitment/interviews') ?>">Interviews</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <!-- Subjects -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-book me-2"></i><span class="menu-text">Subjects</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <li><a href="<?= base_url('admin/add/subject') ?>">Add</a></li>
                <li><a href="<?= base_url('admin/view/subjects') ?>">View</a></li>
                <li><a href="<?= base_url('admin/manage/subjects') ?>">Manage</a></li>
                <li><a href="<?= base_url('admin/assign/subjects/employees') ?>">Teachers</a></li>
            </ul>
        </li>

        <!-- Classes -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-bank me-2"></i><span class="menu-text">Classes</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-bank me-2"></i> Class <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/add/class') ?>">Add</a></li>
                        <li><a href="<?= base_url('admin/view/class') ?>">View</a></li>                        
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-house me-2"></i> Streams <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/add/stream') ?>"><i class="fas fa-plus me-2"></i> Add</a></li>
                        <li><a href="<?= base_url('admin/view/stream') ?>"><i class="fas fa-eye me-2"></i> View</a></li>
                        <li><a href="<?= base_url('admin/assign/stream/employees') ?>"><i class="fas fa-male me-2"></i> Class teacher</a></li>
                        <li><a href="<?= base_url('admin/associate/subject') ?>"><i class="fas fa-book me-2"></i> Associate Subjects</a></li>
                    </ul>
                </li>
                <li><a href="<?= base_url('admin/manage/subjects') ?>"><i class="fas fa-file-alt me-2"></i> Reports</a></li>
            </ul>
        </li>

        <!-- Examinations -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-file-excel me-2"></i><span class="menu-text">Exams</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-file-excel me-2"></i> Examination <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/view/exams') ?>">View</a></li>                        
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-file-pdf me-2"></i> Results <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/record/exams') ?>"><i class="fas fa-table me-2"></i> Record</a></li>
                        <li><a href="<?= base_url('admin/view/results') ?>"><i class="fas fa-table me-2"></i> View</a></li>
                        <li><a href="#"><i class="fas fa-envelope me-2"></i> SMS</a></li>
                        <li><a href="<?= base_url('admin/view/exams') ?>"><i class="fas fa-file-word me-2"></i> Report Cards</a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- Transport module -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-bus me-2"></i><span class="menu-text">Students Transport</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <!-- Tours -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">Tour <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/trans/tours') ?>">Manage Tours</a></li>
                        <li><a href="<?= base_url('admin/trans/stations/students') ?>">Sudents Assignments</a></li>
                        <li><a href="<?= base_url('admin/tours/students/reports') ?>">Reports</a></li>
                    </ul>
                </li>
                <!-- Routes under Tours -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle">Routes <i class="fas fa-chevron-down ms-auto"></i></a>
                    <ul class="submenu list-unstyled ps-3 collapse">
                        <li><a href="<?= base_url('admin/trans/routes') ?>">Manage</a></li>
                        <li><a href="<?= base_url('admin/trans/stations') ?>">Stations</a></li>
                        <li><a href="<?= base_url('admin/trans/buses') ?>">Vehicle</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <!-- Settings -->
        <li class="has-submenu">
            <a href="#" class="d-flex align-items-center submenu-toggle">
                <i class="fas fa-users me-2"></i><span class="menu-text">Settings</span><i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="submenu list-unstyled ps-3 collapse">
                <li><a href="<?= base_url('admin/academic-years') ?>">Academic Years</a></li>
                <li><a href="<?= base_url('admin/terms') ?>">Terms</a></li>
                <li><a href="<?= base_url('admin/manage/students') ?>">Calender</a></li>
                <li><a href="<?= base_url('admin/reports/students') ?>">Timetable</a></li>
            </ul>
        </li>
        <!-- Logout -->
        <li>
            <a href="<?= base_url('logout') ?>" class="d-flex align-items-center">
                <i class="fas fa-sign-out-alt me-2"></i><span class="menu-text">Logout</span>
            </a>
        </li>
    </ul>
</div>