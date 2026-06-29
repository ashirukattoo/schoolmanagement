<?= $this->extend('academic/assets/default'); ?>
<?= $this->section('content'); ?>
    <!-- Dashboard Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <!--begin::Col-->
            <div class="col-lg-3 col-md-6 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><?= $students ?></h3>
                    <p>Total Students</p>
                  </div>
                  <i class="small-box-icon fas fa-users"></i>
                  <a
                    href="<?= base_url('admin/view/students') ?>"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-success">
                  <div class="inner">
                    <h3><?= $employees ?></h3>
                    <p>Total Staff</p>
                  </div>
                  <i class="small-box-icon fas fa-chalkboard-teacher"></i>
                  <a
                    href="<?= base_url('admin/view/employees') ?>"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-info">
                  <div class="inner">
                    <h3><?= $subjects ?></h3>
                    <p>Total Subjects</p>
                  </div>
                  <i class="small-box-icon fas fa-book"></i>
                  <a
                    href="<?= base_url('admin/view/subjects') ?>"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-warning">
                  <div class="inner">
                    <h3><?= $exams ?></h3>
                    <p>Total Examinations</p>
                  </div>
                  <i class="small-box-icon fas fa-folder-open"></i>
                  <a
                    href="<?= base_url('admin/view/exams') ?>"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                  >
                    More info <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 1-->
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
      // Enrollment Chart
      const enrollmentChart = new Chart(
          document.getElementById('enrollmentChart'),
          {
              type: 'bar',
              data: {
                  labels: <?= $enrollmentLabels ?>,
                  datasets: [{
                      label: "Students",
                      data: <?= $enrollmentValues ?>,
                      backgroundColor: [
                          'rgba(65, 99, 132, 0.9)',
                          'rgba(65, 99, 135, 0.9)',                          
                          '#177d4e',
                          '#177d4e',
                          'rgba(75, 192, 192, 0.6)',
                          'rgba(153, 102, 255, 0.6)',
                          'rgba(54, 162, 235, 0.6)',
                          'rgba(255, 159, 196, 0.9)',
                      ],
                      borderWidth: 1
                  }]
              }
          }
      );

      // Performance Chart
      const performanceChart = new Chart(
          document.getElementById('performanceChart'),
          {
              type: 'line',
              data: {
                  labels: <?= $performanceLabels ?>,
                  datasets: [{
                      label: "Average Score",
                      data: <?= $performanceValues ?>,
                      fill: true,
                      tension: 0.3,
                      backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255,0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                      borderWidth: 1
                  }]
              }
          }
      );
  });
</script>

<?= $this->endSection(); ?>
