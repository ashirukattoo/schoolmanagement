<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
<div class="container mt-0">
    <div class="card mx-auto mb-4 shadow-sm">
        <div class="card-header bg-info">
            <h6><?= $pageTitle ?>
            <a href="<?= base_url('admin/trans/stations/students/assign_route') ?>" class="btn btn-primary me-3">Assign New</a></h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Station</th>
                        <th>Route</th>
                        <th>Assigned By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $row): ?>
                    <tr>
                        <td><?= $row['stuFname'].' '.$row['stuSurname'] ?></td>
                        <td><?= $row['staName'] ?></td>
                        <td><?= $row['rouName'] ?></td>
                        <td><?= $row['empFname'] ?? '—' ?></td>
                        <td><?= $row['assigned_at'] ?></td>
                        <td><a href="<?= base_url('student_routestation/delete/'.$row['srsID']) ?>" class="btn btn-danger btn-sm">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>