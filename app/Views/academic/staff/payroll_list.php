<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>

<div class="container mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Manage Payroll Runs</h5>
        </div>

        <div class="card-body">

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Processed At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($payrolls as $p): ?>
                        <tr>
                            <td><?= esc($p['payroll_month']) ?></td>
                            <td><?= esc($p['payroll_year']) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $p['status'] == 'Draft' ? 'warning' : 
                                    ($p['status'] == 'Approved' ? 'success' : 'dark') ?>">
                                    <?= esc($p['status']) ?>
                                </span>
                            </td>
                            <td><?= esc($p['processed_at']) ?></td>
                            <td>
                                <a href="<?= site_url('admin/payroll/view/'.$p['payroll_id']) ?>" 
                                   class="btn btn-sm btn-info">View</a>

                                <?php if($p['status'] == 'Draft'): ?>
                                    <a href="<?= site_url('admin/payroll/approve/'.$p['payroll_id']) ?>" 
                                       class="btn btn-sm btn-success">Approve</a>

                                    <a href="<?= site_url('admin/payroll/delete/'.$p['payroll_id']) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this payroll?')">
                                       Delete
                                    </a>
                                <?php endif; ?>

                                <?php if($p['status'] == 'Approved'): ?>
                                    <a href="<?= site_url('admin/payroll/paid/'.$p['payroll_id']) ?>" 
                                       class="btn btn-sm btn-dark">Mark Paid</a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
