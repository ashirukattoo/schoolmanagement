<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p-0 shadow-sm">
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
                <form action="<?= base_url('admin/trans/buss/add') ?>" method="post">
                    <div class="row">
                        <div class="col-md-2 mb-1">
                            <label>Plate Number</label>
                            <input type="text" name="vePlateNumber" class="form-control" placeholder="E.g EDL456" required>
                        </div>
                        <div class="col-md-2 mb-1">
                            <label>Model</label>
                            <input type="text" name="veModel" class="form-control" placeholder="E.g: YUTONG XLP" required>
                        </div>                    
                        <div class="col-md-2 mb-1">
                            <label>Ownershp</label>
                            <select name="veOwnership" class="form-control" required>
                                <option>Self</option>
                                <option>Rent</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label>Named</label>
                            <input type="text" name="veNamed" class="form-control" placeholder="E.g Bus 01"  required>
                        </div>
                        <div class="col-md-2 mb-1 mt-4">
                            <button class="btn btn-info w-10">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mx-auto mt-1 p-0 shadow-sm">
            <div class="card-header bg-info text-white"><span class="mb-0">Recorded Vehicles</span></div>
            <div class="card-body">
                <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Named</th>
                            <th>Ownership</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($vehicles)):
                        $count=1;
                     ?>
                        <?php foreach ($vehicles as $s): ?>
                            <tr class="table-sm">
                                <td><?= $count; ?></td>
                                <td><?= $s['vePlateNumber'].' '.$s['veModel'] ?></td>
                                <td><?= $s['veNamed'] ?></td>
                                <td><?= $s['veOwnership'] ?? 'NIL' ?></td>
                                <td>
                                    <a href="?view_id=<?= $s['veID'] ?>" class="btn  btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                    <a href="?edit_id=<?= $s['veID'] ?>" class="btn  btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="?del_id=<?= $s['veID'] ?>" class="btn btn-sm btn-secondary"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="5">No Routes found.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>