<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                Payslip - <?= esc($item['payroll_month']) ?>/<?= esc($item['payroll_year']) ?>
            </h5>
        </div>

        <div class="card-body">

            <h6>Employee Details</h6>
            <p>
                <strong>Name:</strong> 
                <?= esc(strtoupper($item['empFname'].' '.$item['empMname'].' '.$item['empSurname'])) ?><br>
                <strong>Employee ID:</strong> <?= esc($item['empID']) ?>
            </p>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6>Earnings</h6>
                    <table class="table table-bordered">
                        <tr><td>Basic</td><td><?= number_format($item['basic_salary'],2) ?></td></tr>
                        <tr><td>Housing</td><td><?= number_format($item['housing_allowance'],2) ?></td></tr>
                        <tr><td>Transport</td><td><?= number_format($item['transport_allowance'],2) ?></td></tr>
                        <tr><td>Medical</td><td><?= number_format($item['medical_allowance'],2) ?></td></tr>
                        <tr><td>Other</td><td><?= number_format($item['other_allowance'],2) ?></td></tr>
                        <tr class="table-success">
                            <td><strong>Gross</strong></td>
                            <td><strong><?= number_format($item['gross_salary'],2) ?></strong></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6>Deductions</h6>
                    <table class="table table-bordered">
                        <?php $dedTotal = 0; ?>
                        <?php foreach($deductions as $d): ?>
                            <?php $dedTotal += $d['amount']; ?>
                            <tr>
                                <td><?= esc($d['deduction_name']) ?></td>
                                <td><?= number_format($d['amount'],2) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="table-danger">
                            <td><strong>Total Deductions</strong></td>
                            <td><strong><?= number_format($dedTotal,2) ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <h5 class="text-end">
                Net Salary: 
                <span class="text-primary">
                    <?= number_format($item['net_salary'],2) ?>
                </span>
            </h5>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
