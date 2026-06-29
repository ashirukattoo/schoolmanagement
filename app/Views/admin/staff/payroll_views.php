<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Payroll Details - <?= esc($payroll['payroll_month']) ?>/<?= esc($payroll['payroll_year']) ?>
            </h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Basic</th>
                        <th>Gross</th>
                        <th>Total Deductions</th>
                        <th>Net Salary</th>
                        <th>Payslip</th>
                    </tr>
                </thead>
                <tbody>

                <?php 
                $totalGross = 0;
                $totalDeduction = 0;
                $totalNet = 0;
                ?>

                <?php foreach($items as $item): ?>

                    <?php 
                        $totalGross += $item['gross_salary'];
                        $totalDeduction += $item['total_deductions'];
                        $totalNet += $item['net_salary'];
                    ?>

                    <tr>
                        <td><?= esc($item['employee']) ?></td>
                        <td><?= number_format($item['basic_salary'],2) ?></td>
                        <td><?= number_format($item['gross_salary'],2) ?></td>
                        <td><?= number_format($item['total_deductions'],2) ?></td>
                        <td><strong><?= number_format($item['net_salary'],2) ?></strong></td>
                        <td>
                            <a href="<?= site_url('admin/payroll/payslip/'.$item['item_id']) ?>" 
                               class="btn btn-sm btn-secondary">
                               View Payslip
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

                <tfoot>
                    <tr class="bg-light">
                        <th>Total</th>
                        <th></th>
                        <th><?= number_format($totalGross,2) ?></th>
                        <th><?= number_format($totalDeduction,2) ?></th>
                        <th><?= number_format($totalNet,2) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
