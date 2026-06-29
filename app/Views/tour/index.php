<?= $this->extend('templates/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-1">
    <div class="card">
        <div class="card-header bg-secondary text-white">   
            <h5>Students Arrangement on Stations</h5>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Route <strong>Station</strong></th>
                        <th>Assigned By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($studentRoutes)):
                    $count=1;
                 ?>
                    <?php foreach ($studentRoutes as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['stuFname']." ".$s['stuMname']." ".$s['stuSurname'] ?></td>
                            <td><?= $s['rouName'].' <strong>('.$s['staName'].')</strong>'; ?></td>
                            <td><?= $s['empFname'].' '.$s['empSurname'] ?></td>
                            <td><?= $s['status'] ?? 0?></td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No record found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header bg-secondary text-white">   
            <h5 class="pt-0">Vehicles Vs Employment Assignments</h5>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tour</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Employee</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($tourRoutes)):
                    $count=1;
                 ?>
                    <?php foreach ($tourRoutes as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['touName'] ?></td>
                            <td><?= $s['rouName'] ?></td>
                            <td><?= $s['vePlateNumber'] ?? 'NIL' ?></td>
                            <td><?= $s['first_name'].' '.$s['surname'] ?></td>
                            <td><?= $s['trDeparture'] ?></td>
                            <td><?= $s['trArrival'] ?></td>
                            <td><?= $s['trStatus'] ?? 0?></td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="8">No Record found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>