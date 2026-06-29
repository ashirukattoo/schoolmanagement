<?= $this->extend('templates/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-secondary text-white">   
            <span>Employees Team</span>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Position</th>
                        <th>Hire Date</th>
                        <th>Manage</th>
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
                            <td><?= $s['empOccupasion'] ?? 'NIL' ?></td>
                            <td><?= $s['empHired'] ?></td>
                            <td>
                                <a class="btn btn-secondary" href="?edi_id=<?= $s['empID'] ?> "><i class="fas fa-edit"></i></a>
                                <a class="btn btn-secondary" href="?del_id=<?= $s['empID'] ?> "><i class="fas fa-close"></i></a> 
                                <a class="btn btn-secondary" href="?man_id=<?= $s['empID'] ?> "><i class="fas fa-plus"></i></a>              
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