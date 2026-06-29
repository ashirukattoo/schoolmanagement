<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white">   
            <h6>Students Registered</h6>
        </div>
        <div class="card-body">
        <a class="btn btn-sm btn-outline-info" href="<?= base_url('admin/export_students_list/') ?>">Export Students</a>
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Class</th>
                        <th>Guardian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($students)):
                    $count=1;
                 ?>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['stuFname']." ".$s['stuMname']." ".$s['stuSurname'] ?></td>
                            <td><?= $s['stuSex'] ?></td>
                            <td><?= $s['class'] ?>-<?= $s['stream'] ?></td>
                            <td><?= $s['guardian'] ?? 'NIL' ?></td>
                            <td><?= $s['stuStatus'] ?></td>
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
</div>
<?= $this->endSection() ?>