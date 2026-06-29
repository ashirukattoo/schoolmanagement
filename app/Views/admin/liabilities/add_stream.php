<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white"><h6 class="mb-0"><?= $pageTitle ?></h6></div>
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
                <form action="<?= base_url('admin/save/stream') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Stream/Combination Name</label>
                            <input type="text" name="stream" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Class</label>
                            <select name="class" class="form-control" required>
                                <?php if (isset($classes)) {
                                    foreach ($classes as $s) { ?>
                                        <option value="<?= $s['cid'] ?>"><?= $s['named'] ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mt-4">
                            <button class="btn btn-info w-10 mx-auto text-white">Add</button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
        <div class="card mt-4">
        <div class="card-header bg-info text-white">   
            <h6>Streams</h6>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stream</th>                  
                        <th>Status</th>
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
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No students found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>