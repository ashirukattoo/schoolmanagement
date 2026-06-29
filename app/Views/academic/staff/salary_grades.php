<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <h4 class="alert alert-info">Salary Grades (Daraja)</h4>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('admin/saveSalaryGrade') ?>" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label>Grade Name</label>
                    <input type="text" name="grade_name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Basic Salary</label>
                    <input type="number" step="0.01" name="basic_salary" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Housing Allowance</label>
                    <input type="number" step="0.01" name="housing_allowance" class="form-control">
                </div>

                <div class="col-md-4 mt-3">
                    <label>Transport Allowance</label>
                    <input type="number" step="0.01" name="transport_allowance" class="form-control">
                </div>

                <div class="col-md-4 mt-3">
                    <label>Medical Allowance</label>
                    <input type="number" step="0.01" name="medical_allowance" class="form-control">
                </div>

                <div class="col-md-4 mt-3">
                    <label>Other Allowance</label>
                    <input type="number" step="0.01" name="other_allowance" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-info mt-3">Save Grade</button>
        </form>

        <hr>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Grade</th>
                    <th>Basic</th>
                    <th>Housing</th>
                    <th>Transport</th>
                    <th>Medical</th>
                    <th>Other</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($grades as $grade): ?>
                    <tr>
                        <td><?= esc($grade['grade_name']) ?></td>
                        <td><?= number_format($grade['basic_salary'],2) ?></td>
                        <td><?= number_format($grade['housing_allowance'],2) ?></td>
                        <td><?= number_format($grade['transport_allowance'],2) ?></td>
                        <td><?= number_format($grade['medical_allowance'],2) ?></td>
                        <td><?= number_format($grade['other_allowance'],2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>