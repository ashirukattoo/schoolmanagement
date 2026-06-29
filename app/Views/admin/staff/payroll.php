<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p0 shadow-sm">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><?= $pageTitle ?></h5></div>
            <div class="card-body">
                <h3>Process Payroll</h3>
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <form method="post" action="<?= site_url('admin/process/payroll') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Month</label>
                            <select name="month" class="form-control" required>
                                <?php for($m=1; $m<=12; $m++): ?>
                                    <option value="<?= $m ?>"><?= date("F", mktime(0,0,0,$m,1)) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                   <table class="table" border="1" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Basic Salary</th>
                                <th>Transport Allw</th>
                                <th>House Allw</th>
                                <th>Total Earn</th>
                                <th>Deduction</th>
                                <th>Net Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total_basic_salary     = 0;
                                $total_trans_allowance  = 0;
                                $total_house_allowance  = 0;
                                $total_earns            = 0;
                                $total_deductions       = 0;
                                $total_net_pay          = 0;

                                foreach ($employees as $emp): 

                                $basic      = (float)$emp['basic_salary'];
                                $transport  = (float)$emp['transport_allowance'];
                                $house      = (float)$emp['housing_allowance'];

                                $gross      = $basic + $transport + $house;

                                // Example deduction logic (5% statutory – adjust later properly)
                                $deduction  = $basic * 0.05;

                                $net        = $gross - $deduction;

                                // Accumulate totals
                                $total_basic_salary     += $basic;
                                $total_trans_allowance  += $transport;
                                $total_house_allowance  += $house;
                                $total_earns            += $gross;
                                $total_deductions       += $deduction;
                                $total_net_pay          += $net;
                            ?>
                            <tr>
                                <td><?= esc($emp['empFname'] . ' ' . $emp['empSurname']) ?></td>
                                <td><?= esc($emp['grade_name']) ?></td>
                                <td><?= number_format($basic, 2) ?></td>
                                <td><?= number_format($transport, 2) ?></td>
                                <td><?= number_format($house, 2) ?></td>
                                <td><strong><?= number_format($gross, 2) ?></strong></td>
                                <td class="text-danger"><?= number_format($deduction, 2) ?></td>
                                <td class="text-success"><strong><?= number_format($net, 2) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot class="table-light">
                        <tr>
                            <th colspan="2">TOTAL</th>
                            <th><?= number_format($total_basic_salary, 2) ?></th>
                            <th><?= number_format($total_trans_allowance, 2) ?></th>
                            <th><?= number_format($total_house_allowance, 2) ?></th>
                            <th><?= number_format($total_earns, 2) ?></th>
                            <th><?= number_format($total_deductions, 2) ?></th>
                            <th><?= number_format($total_net_pay, 2) ?></th>
                        </tr>
                        </tfoot>

                    <br>
                    <button class="btn btn-sm btn-info" type="submit">Process Payroll</button>
                </form>

            </div>
        </div>
    </div>
<?= $this->endSection() ?>