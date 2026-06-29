<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white"><h3 class="mb-0"><?= $pageTitle ?></h3></div>
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
                <form action="<?= base_url('admin/update/student') ?>" method="post">
                    <?= csrf_field() ?>
                    <?php foreach ($student as $r) { ?>
                        <div class="row">
                            <input type="hidden" name="id" value="<?= $r['stuID'] ?>" >
                            <div class="col-md-4 mb-3">
                                <label>First Name</label>
                                <input type="text" name="fname" value="<?= $r['stuFname'] ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Middle Name</label>
                                <input type="text" value="<?= $r['stuMname'] ?? ' ' ?>" name="mname" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Surname</label>
                                <input type="text" value="<?= $r['stuSurname'] ?>" name="lname" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label>Sex</label>
                                <select name="sex" class="form-control">
                                    <option selected><?= $r['stuSex'] ?></option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Birth Date</label>
                                <input type="date" value="<?= $r['stuDob'] ?>" name="dob" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Class</label>
                            <select name="class" class="form-control" required>
                                <?php if (isset($streams)) {
                                    foreach ($streams as $s) { 
                                        if ($r['stream_id'] === $s['sid']) { ?>
                                            <option value="<?= $s['sid'] ?>" selected><?= $s['class'] ?>-<?= $s['sName'] ?></option>
                                        <?php }else {  ?>
                                        <option value="<?= $s['sid'] ?>"><?= $s['class'] ?>-<?= $s['sName'] ?></option>
                                    <?php } }
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Guardian</label>
                            <select name="guardian" class="form-control" required>
                                <?php if (isset($guardians)) { ?>
                                    <option > -- Select Guardian --</option> <?php
                                    foreach ($guardians as $s) { 
                                        if ($r['guardian_id'] === $s['guID']) { ?>
                                            <option value="<?= $s['guID'] ?>" selected><?= $s['empname'] ?></option>
                                        <?php }else {  ?>
                                        <option value="<?= $s['guID'] ?>"><?= $s['empname'] ?></option>
                                    <?php } }
                                } ?>
                            </select>
                        </div>
                   <?php } ?>
                    <button class="btn btn-info w-10 mx-auto text-white">Update</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>