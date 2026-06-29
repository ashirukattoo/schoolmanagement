<?= $this->extend('teacher/includes/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-secondary text-white p-1">   
            <h6>Students Registered</h6>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
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
                        <tr class="p-0 m-0">
                            <td><?= $count; ?></td>
                            <td><?= $s['stuFname']." ".$s['stuMname']." ".$s['stuSurname'] ?></td>
                            <td><?= $s['class'] ?></td>
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