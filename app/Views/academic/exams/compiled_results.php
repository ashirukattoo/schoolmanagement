<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid mt-3">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= esc($pageTitle) ?></h5>
            <a href="<?= base_url('/admin/exams') ?>" class="btn btn-light btn-sm">← Back</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Total Points</th>
                        <th>Division</th>
                        <th>Remark</th>
                        <th>Position</th>
                        <th>Subjects</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($results as $index => $r): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($r['student_name']) ?></td>
                            <td><?= esc($r['class_name'] ?? '-') ?></td>
                            <td><strong><?= esc($r['total_points']) ?></strong></td>
                            <td><span class="badge bg-primary"><?= esc($r['division']) ?></span></td>
                            <td><?= esc($r['remark']) ?></td>
                            <td><strong><?= esc($r['position']) ?></strong></td>
                            <td>
                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="collapse" data-bs-target="#subjects<?= $r['crID'] ?>">View</button>
                            </td>
                        </tr>
                        <tr class="collapse" id="subjects<?= $r['crID'] ?>">
                            <td colspan="8">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th>Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($r['subjects'] as $s): ?>
                                            <tr>
                                                <td><?= esc($s['subject_code']) ?></td>
                                                <td><?= esc($s['subject_name']) ?></td>
                                                <td><?= esc($s['score']) ?></td>
                                                <td><?= esc($s['grade']) ?></td>
                                                <td><?= esc($s['point']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>