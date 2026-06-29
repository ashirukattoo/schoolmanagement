<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p0 shadow-sm">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><?= $pageTitle ?></h5></div>
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
                <form action="<?= base_url('admin/import/employee') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/employee/template') ?>" class="btn btn-outline-primary">
                                Employee Hering Form Template
                            </a>
                        </div>                            
                        <div class="col-md-5 col-sm-8">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-sm-4 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/add/employees') ?>" method="post">
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
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>                        
                    <div class="mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Sex</label>
                        <select name="sex" class="form-control">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="Teacher">Teacher</option>
                                <option value="Technician">Technician</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Position</label>
                            <select name="position" class="form-control">
                                <option value="Subject Master">Subject Master</option>
                                <option value="Discipline">Discipline</option>
                                <option value="Specific Task">Specific Task</option>
                                <option value="Labtechnician">Labtechnician</option>
                                <option value="Secretary">Secretary</option>
                                <option value="Academic">Academic</option>
                                <option value="Deputy">Deputy</option>
                                <option value="Head">Head</option>
                                <option value="Class Master">Class Master</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Hired</label>
                            <input type="date" name="hired" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Salary Grade (Daraja)</label>
    <select name="empSalaryGradeID" class="form-control" required>
        <option value="">-- Select Grade --</option>
        <?php foreach($grades as $grade): ?>
            <option value="<?= $grade['grade_id'] ?>">
                <?= esc($grade['grade_name']) ?> 
                (<?= number_format(
                    $grade['basic_salary'] 
                    + $grade['housing_allowance'] 
                    + $grade['transport_allowance'] 
                    + $grade['medical_allowance'] 
                    + $grade['other_allowance'], 2) ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>

                    <button class="btn btn-info w-10 text-end">Save</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>