<?= $this->extend('templates/default'); ?>
<?= $this->section('content'); ?>
    <!-- Dashboard Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-primary text-white p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <!-- Large icon on the left -->
                        <i class="fas fa-users fa-4x me-3"></i>
                        <!-- Text content on the right -->
                        <div>
                            <h6 class="mb-1">Recorded Students</h6>
                            <h3 id="AdmittedStd" class="mb-0"><?= $students ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-info text-white p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <!-- Large icon on the left -->
                        <i class="fas fa-chalkboard-teacher fa-4x me-3"></i>
                        <!-- Text content on the right -->
                        <div>
                            <h6 class="mb-1">Recorded Employee</h6>
                            <h3 id="activeTch" class="mb-0"><?= $employees ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-warning text-white p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <!-- Large icon on the left -->
                        <i class="fas fa-road fa-4x me-3"></i>
                        <!-- Text content on the right -->
                        <div>
                            <h6 class="mb-1">Registered Routes</h6>
                            <h3 id="pendingReports" class="mb-0"><?= $routes ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-success text-white p-3 shadow-sm">
                    <div class="d-flex align-items-center">
                        <!-- Large icon on the left -->
                        <i class="fas fa-bus fa-4x me-3"></i>
                        <!-- Text content on the right -->
                        <div>
                            <h6 class="mb-1">Vehicles</h6>
                            <h3 id="examReport" class="mb-0"><? $vehicles ?? 0 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4 g-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Student Enrollment</h6>
                    <canvas id="enrollmentChart" class="chart-container"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h6>Performance Overview</h6>
                    <canvas id="performanceChart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection(); ?>
