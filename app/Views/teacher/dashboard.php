<?= $this->extend('teacher/includes/default'); ?>
<?= $this->section('content'); ?>
    <!-- Dashboard Cards -->
    <div class="container-fluid p-4">
        <div class="row g-4">
            <!--begin::Col-->
            <div class="col-lg-3 col-md-6 col-6">
                <!--begin::Small Box Widget 1-->
                <div class="small-box text-bg-primary">
                  <div class="inner">
                    <h3><?= $students ?? 0 ?></h3>
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
                    <h3><?= $employees ?? 0  ?></h3>
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
                    <h3><?= $subjects ?? 0 ?></h3>
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
                    <h3><?= $exams ?? 0 ?></h3>
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
    </div>
<?= $this->endSection(); ?>
