<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p-4 shadow-sm">
            <div class="card-header"><h3 class="text-center mb-0">🧾 <?= $pageTitle ?></h3></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url('students/save') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Middle Name</label>
                            <input type="text" name="mname" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Surname</label>
                            <input type="text" name="lname" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label>Sex</label>
                            <select name="sex" class="form-control">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Birth Date</label>
                            <input type="date" name="dob" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Class</label>
                        <select name="class" class="form-control" required>
                            <option value="1">Form I</option>
                            <option value="2">Form II</option>
                            <option value="3">Form III</option>
                            <option value="4">Form IV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Guardian</label>
                        <input type="text" name="guardian" class="form-control">
                    </div>
                    <button class="btn btn-success w-25 mx-auto">Register</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>