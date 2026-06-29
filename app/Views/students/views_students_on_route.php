<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>

<h3>Student Route–Station Assignments</h3>
<a href="<?= base_url('student_routestation/create') ?>" class="btn btn-primary mb-3">Assign New</a>

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

<?= $this->endSection() ?>