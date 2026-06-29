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
                        <div class="col-md-12 mb-12">
                            <h5>Bulk Assignment</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/assign/subjects/employees') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-12">
                            <h5><?= $pageTitle ?></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Teacher</label>
                            <select name="employee" class="form-control">
                                <option >-- Select Employee--</option>
                                <?php if (isset($teachers)):
                                    foreach ($teachers as $s ): ?>
                                        <option value="<?= $s['empID'] ?>"><?= $s['empFname']." ".$s['empSurname'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Stream</label>
                            <select name="stream" class="form-control">
                                <option >-- Select Stream--</option>
                                <?php if (isset($streams)):
                                    foreach ($streams as $s ): ?>
                                        <option value="<?= $s['sid'] ?>"><?= $s['class']." ".$s['sName'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Subject</label>
                            <select name="subject" class="form-control">
                                <option >-- Select Subject--</option>
                                <?php if (isset($subjects)):
                                    foreach ($subjects as $s ): ?>
                                        <option value="<?= $s['subID'] ?>"><?= $s['subName'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Date assigned</label>
                            <input type="date" name="assigndate" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>To Date </label>
                            <input type="date" name="todate" class="form-control" >
                        </div>
                    </div>
                    <button name="assigneTeacher" class="btn btn-info w-10 mx-auto text-white">Save</button>
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
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($workers)):
                        $count=1;
                     ?>
                        <?php foreach ($workers as $s): ?>
                             <tr >
                                <td><?= $count; ?></td>
                                <td><?= $s['class'] ?></td>
                                <td><?= $s['subject'] ?></td>
                                <td><?= $s['teacher'] ?></td>
                                <td>
                                    <a href="<?= base_url('admin/manage/student') ?>?mandatory=<?= $s['id'] ?>" class="btn btn-sm btn-danger" title="Delete "><i class="fas fa-close"></i></a>
                                    <a href="<?= base_url('admin/manage/student') ?>?ddd=<?= $s['id'] ?>" class="btn btn-sm btn-info" title="Transfer "><i class="fas fa-arrow-right"></i></a>
                                    <a href="<?= base_url('admin/manage/student') ?>?ddddd=<?= $s['id'] ?>" class="btn btn-sm btn-primary" title="Edit "><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            <?php  
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="5">No Record found.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>