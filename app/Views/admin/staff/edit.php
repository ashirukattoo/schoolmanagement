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
                <form action="<?= base_url('admin/edit/employee') ?>" method="post">
                    <input type="hidden" name="empID" value="<?= $emp['empID'] ?>">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control" value="<?= $emp['empFname'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Middle Name</label>
                            <input type="text" name="mname" value="<?= $emp['empMname'] ?>" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="lname" value="<?= $emp['empSurname'] ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= $emp['empEmail'] ?>" class="form-control" disabled=true>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?= $emp['empDob'] ?>"  class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Sex</label>
                            <select name="sex" class="form-control">
                                <?php if (strtolower($emp['empSex']) =='female') {
                                    echo '<option value="Female" selected>Female</option><option value="Male" >Male</option>';
                                }elseif (strtolower($emp['empSex']) =='male') {
                                    echo '<option value="Female">Female</option><option value="Male" selected>Male</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Grade</label>
                            <select name="empSalaryGradeID" class="form-control">
                                <?php foreach($grades as $grade): ?>
                                    <option value="<?= $grade['grade_id'] ?>"
                                        <?= $emp['empSalaryGradeID'] == $grade['grade_id'] ? 'selected' : '' ?>>
                                        <?= esc($grade['grade_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10 text-end">Save</button>
                </form>
            </div>
        </div>
        <div class="card mx-auto p-1 shadow-sm">
            <div class="card-header bg-info text-white"><h5 class="mb-0">Employee's Roles</h5></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $r) { ?>
                            <tr>
                                <td><?= $r['erRole'] ?></td>
                                <td><?= $r['erStatus'] ?></td>
                                <td>Action</td>
                            </tr>    
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>