<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card">
        <div class="card-header bg-info text-white">   
            <h6><?= $pageTitle ?></h6>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stream</th>                  
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($streams)):
                    $count=1;
                 ?>
                    <?php foreach ($streams as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['class'] ?> - <?= $s['sName'] ?></td>
                            <td><?= $s['sStatus'] ?></td>
                            <td>
                                <?php if(strtolower($s['sStatus']) === 'inactive'): ?>
                                    <a href="<?= base_url('/admin/stream/activate/'.$s['sid']); ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></a>
                                <?php elseif(strtolower($s['sStatus'])==='next'): ?>
                                    <a href="<?= base_url('/admin/stream/activate/'.$s['sid']); ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-close"></i></a>
                                <?php else: ?>
                                    <a href="<?= base_url('/admin/students/attendance/'.$s['sid']); ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-bar-chart"></i></a>
                                    <a href="<?= base_url('/admin/stream/deactivate/'.$s['sid']); ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-close"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No Data Found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>