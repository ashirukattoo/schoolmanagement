<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card card-info card-outlined mx-auto shadow-sm">
            <div class="card-header bg-info text-white p-1"><h6 class="mb-0"><?= $pageTitle ?></h6></div>
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
                        <div class="col-md-3 mb-3">
                            <h5>Bulk Upload</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/associate/subject') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <h5 class="title">Assign Subject To Stream</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-1">
                            <label>Stream</label>
                            <select name="stream" class="form-control">
                                <?php if (isset($streams)):
                                    foreach ($streams as $s ): ?>
                                        <option value="<?= $s['sid'] ?>"><?= $s['class']." ".$s['sName'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-1">
                            <label>Subject</label>
                            <select name="subject" class="form-control">
                                <?php if (isset($subjects)):
                                    foreach ($subjects as $s ): ?>
                                        <option value="<?= $s['subID'] ?>"><?= $s['subName'] ?></option>
                                    <?php endforeach; 
                                endif; ?>
                            </select>
                        </div>
                        <div class=" col-md-2 mb-1">
                            <label class="form-check-label">Is Mandatory?</label>
                            <select name="mandatory" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>                                
                            </select>
                        </div>
                        <div class=" col-md-2 mb-1">
                            <label class="form-check-label">Is Subsidiary?</label>
                            <select name="subsidiary" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class=" col-md-2 mb-1">
                            <label class="form-check-label">Has a practical?</label>
                            <select name="practical" class="form-control">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10 mx-auto text-white">Save</button>
                </form>
            </div>
        </div>
        <div class="card card-info card-outlined mx-auto mt-2">
            <div class="card-header p-1">   
                <h6>Subjects on Streams</h6>
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
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Category</th>
                            <th>Subsidiary</th>
                            <th>Has Practical</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($details)):
                        $count=1;
                     ?>
                        <?php foreach ($details as $s): ?>
                             <tr >
                                <td><?= $count; ?></td>
                                <td><?= $s['subject'] ?></td>
                                <td><?= $s['class'] ?>-<?= $s['stream'] ?></td>
                                <td><?php if ($s['isMandatory'] == '1'){ echo 'Mandatory'; }else{ echo 'Optional'; } ?></td>
                                <td><?php if ($s['isSubsidiary'] == '1'){ echo 'Yes'; }else{ echo 'Not'; } ?></td>
                                <td><?php if ($s['hasPractical'] == '1'){ echo 'Has'; }else{ echo 'Not'; } ?></td>
                                <td>
                                    <a href="<?= base_url('admin/manage/student') ?>?mandatory=<?= $s['scID'] ?>" class="btn btn-sm btn-danger" title="Delete "><i class="fas fa-close"></i></a>
                                    <a href="<?= base_url('admin/manage/student') ?>?ddd=<?= $s['scID'] ?>" class="btn btn-sm btn-info" title="Transfer "><i class="fas fa-arrow-right"></i></a>
                                    <a href="<?= base_url('admin/manage/student') ?>?ddddd=<?= $s['scID'] ?>" class="btn btn-sm btn-primary" title="Edit "><i class="fas fa-edit"></i></a>
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