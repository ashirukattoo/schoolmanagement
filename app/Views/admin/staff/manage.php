<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info">   
            <span>Employees Team</span>
        </div>
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
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Grade</th>
                        <th>Hire Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($employees)):
                    $count=1;
                 ?>
                    <?php foreach ($employees as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['empFname']." ".$s['empMname']." ".$s['empSurname'] ?></td>
                            <td><?= $s['empSex'] ?></td>
                            <td><?= $s['grade'] ?? 'NIL' ?></td>
                            <td><?= $s['empHired'] ?></td>                           
                            <td class="text-nowrap">
                                <!-- ===== Desktop buttons ===== -->
                                <div class="d-none d-md-inline-block">
                                    <a class="btn btn-sm btn-info" href="<?= base_url('admin/employee/'.$s['empID'].'/deductions') ?>"><i class="fas fa-dollar"></i></a>
                                    <a class="btn btn-sm btn-info" href="<?= base_url('admin/edit/employee/'.$s['empID']) ?>"><i class="fas fa-pencil"></i></a> 
                                    <a class="btn btn-sm btn-info" href="<?= base_url('admin/more/employee/'.$s['empID']) ?>"><i class="fas fa-plus"></i></a>
                                    <a class="btn btn-sm btn-danger" href="<?= base_url('admin/del/employee/'.$s['empID']) ?>"><i class="fas fa-close"></i></a> 
                                </div>
                                <!-- ===== Mobile 3-dots dropdown ===== -->
                                <div class="dropdown d-md-none">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-primary" href="<?= base_url('admin/edit/employee/'.$s['empID']) ?>"><i class="fas fa-pencil"></i> Update</a>
                                            <a class="dropdown-item text-primary" href="<?= base_url('admin/employee/'.$s["empID"].'/deductions') ?>"><i class="fas fa-dollar"></i> Earns</a> 
                                            <a class="dropdown-item text-info" href="<?= base_url('admin/more/employee/'.$s['empID']) ?>"><i class="fas fa-plus"></i> More</a>
                                            <a class="dropdown-item text-danger" href="<?= base_url('admin/del/employee/'.$s['empID']) ?>"><i class="fas fa-close"></i> Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No employee found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>