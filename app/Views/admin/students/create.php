<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white"><h3 class="text-center mb-0"><?= $pageTitle ?></h3></div>
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
                <form action="<?= base_url('admin/import/students') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <a href="<?= base_url('admin/template/students') ?>" class="btn btn-primary">
                        Student Template
                    </a>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/save/students') ?>" method="post">
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
                            <?php if (isset($streams)) {
                                foreach ($streams as $s) { ?>
                                    <option value="<?= $s['sid'] ?>"><?= $s['class'] ?>-<?= $s['sName'] ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Guardian</label>
                        <input type="text" name="guardian" class="form-control">
                    </div>
                    <button class="btn btn-info w-10 mx-auto text-white">Register</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>