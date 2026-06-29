<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p0 shadow-sm">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><?= $pageTitle ?></h5></div>
            <div class="card-body">
                <h4>Manage Employee Deductions</h4>

                <form method="post" action="<?= site_url('admin/employee/deductions/save') ?>">

                <input type="hidden" name="empID" value="<?= $empID ?>">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Apply</th>
                            <th>Deduction</th>
                            <th>Type</th>
                            <th>Default Value</th>
                            <th>Custom Override</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($allDeductions as $ded): 
                        $isChecked = in_array($ded['deductionID'], $assignedIDs);
                    ?>

                    <tr>
                        <td>
                            <input type="checkbox"
                                   name="deductions[]"
                                   value="<?= $ded['deductionID'] ?>"
                                   <?= $isChecked ? 'checked' : '' ?>>
                        </td>
                        <td><?= esc($ded['deduction_name']) ?></td>
                        <td><?= esc($ded['deduction_type']) ?></td>
                        <td><?= esc($ded['deduction_value']) ?></td>
                        <td>
                            <input type="number"
                                   name="custom[<?= $ded['deductionID'] ?>]"
                                   class="form-control"
                                   placeholder="Optional">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button class="btn btn-primary">Save Deductions</button>
                </form>         
            </div>
        </div>
    </div>
<?= $this->endSection() ?>