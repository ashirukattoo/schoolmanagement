<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto mb-4 shadow-sm">
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
                <form action="<?= base_url('admin/trans/tours/add') ?>" method="post">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Tour Name</label>
                            <input type="text" name="tour" class="form-control" placeholder="E.g: December Holiday" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option>-- Select Category --</option>
                                <option value="Departure"> Departure </option>
                                <option value="Arrival"> Arrival </option>
                                <option value="Tour"> Tour </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Start Date</label>
                            <input type="date" name="start" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>End Date</label>
                            <input type="date" name="end" required>
                        </div>
                    </div>
                    <button class="btn btn-info w-10">Add</button>
                </form>
            </div>
        </div>
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white"><h6 class="mb-0">Routes</h6></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('delete')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('delete') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('undelete')) { ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('undelete') ?>
                    </div>
                <?php } ?>
                <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Route</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($activeRoutes)):
                        $count=1;
                     ?>
                        <?php foreach ($activeRoutes as $s): ?>
                            <tr>
                                <td><?= $count; ?></td>
                                <td><?= $s['rouName'] ?></td>
                                <td><?= $s['rouStart'] ?></td>
                                <td><?= $s['rouEnd'] ?? 'NIL' ?></td>
                                <td>
                                    <a href="?view_id=<?= $s['rouID'] ?>" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                                    <a href="<?= base_url('admin/trans/route/edit/'.$s['rouID']); ?>" class="btn btn-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="<?= base_url('trans/route') ?>?del_id=<?= $s['rouID'] ?>" class="btn btn-secondary"><i class="fas fa-trash"></i></a>
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