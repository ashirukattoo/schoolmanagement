<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Payroll Reports</h5>
        </div>

        <div class="card-body">

            <!-- Filter Form -->
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label>Month</label>
                    <input type="number" name="month" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" required>
                </div>
                <div class="col-md-4 align-self-end">
                    <button class="btn btn-primary w-100">Generate Report</button>
                </div>
            </form>

            <?php if(isset($payroll)): ?>

                <hr>

                <h6>Summary for <?= esc($payroll['payroll_month']) ?>/<?= esc($payroll['payroll_year']) ?></h6>

                <table class="table table-bordered">
                    <tr>
                        <th>Total Gross</th>
                        <td><?= number_format($totalGross,2) ?></td>
                    </tr>
                    <tr>
                        <th>Total Deductions</th>
                        <td><?= number_format($totalDeduction,2) ?></td>
                    </tr>
                    <tr class="table-success">
                        <th>Total Net Paid</th>
                        <td><strong><?= number_format($totalNet,2) ?></strong></td>
                    </tr>
                </table>

                <h6 class="mt-4">Deduction Breakdown</h6>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Deduction</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($deductions as $d): ?>
                            <tr>
                                <td><?= esc($d['deduction_name']) ?></td>
                                <td><?= number_format($d['total_amount'],2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= site_url('admin/payroll/reports/pdf?month='.$_GET['month'].'&year='.$_GET['year']) ?>" 
                   class="btn btn-danger">
                   Download PDF
                </a>

                <a href="<?= site_url('admin/payroll/reports/excel?month='.$_GET['month'].'&year='.$_GET['year']) ?>" 
                   class="btn btn-success">
                   Download Excel
                </a>


            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
