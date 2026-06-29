<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card card-info card-outlined mx-auto shadow-sm">
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
                <form action="<?= base_url('admin/import/subject') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Bulk Assignment</h5>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a class="btn btn-primary w-10 mx-auto text-white"><i class="fas fa-arrow-down"></i>Template</a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/assign/stream/employees') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Teacher</label>
                            <select name="employee" class="form-control" required>
                                <option >-- Select Employee--</option>
                                <?php if (isset($teachers)):
                                    foreach ($teachers as $s ): ?>
                                        <option value="<?= $s['empID'] ?>"><?= $s['empFname']." ".$s['empSurname'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Stream</label>
                            <select name="stream" class="form-control" required>
                                <option >-- Select Stream--</option>
                                <?php if (isset($streams)):
                                    foreach ($streams as $s ): ?>
                                        <option value="<?= $s['sid'] ?>"><?= $s['class']." ".$s['sName'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Date assigned</label>
                            <input type="date" name="assigndate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>To Date </label>
                            <input type="date" name="todate" class="form-control" >
                        </div>
                        <div class="col-md-3">
                            <p></p>
                            <button name="assignClassMaster" class="btn btn-info w-10 mx-auto text-white">Save</button>
                        </div>
                    </div>                    
                </form>
            </div>
        </div>
        <div class="card card-info card-outlined mx-auto mt-2">
            <div class="card-header ">   
                <h6>Assigned Teacher</h6>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error1')) { ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error1') ?>
                        </div>
                    <?php }elseif (session()->getFlashdata('success1')) { ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success1') ?>
                        </div>
                    <?php } ?>
                <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                    <thead>
                        <tr class="table-info">
                            <th>#</th>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th>From</th>
                            <th>Until</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($workers)):
                        $count=1;
                     ?>
                        <?php foreach ($workers as $s): ?>
                             <tr >
                                <td><?= $count; ?></td>
                                <td><?= $s['class'] ?></td>
                                <td><?= $s['teacher'] ?></td>
                                <td><?= $s['since'] ?></td>
                                <td><?= $s['until'] ?? 'Today' ?></td>
                                <td>
                                     <a href="<?= base_url('admin/update/stream/employees/'.$s['id'].'/Old') ?>" class="btn btn-sm btn-danger" title="Stop "><i class="fas fa-stop"></i></a>
                                    <a href="<?= base_url('admin/update/stream/employees/'.$s['id'].'/Canceled') ?>" class="btn btn-sm btn-info" title="Cancel "><i class="fas fa-arrow-right"></i></a>
                                     <a href="<?= base_url('admin/update/stream/employees/'.$s['id'].'/Active') ?>" class="btn btn-sm btn-primary" title="Return "><i class="fas fa-check"></i></a>
                                </td>
                            </tr>
                            <?php  
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="6">No Any Record found.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>