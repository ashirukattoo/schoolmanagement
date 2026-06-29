<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-1">    
    <div class="card">
        <div class="card-header"><h4>Examination Analytics</h4></div>
        <div class="card-body">
            <?php if($analytics): ?>
                <?php $grades = json_decode($analytics['grade_summary'], true); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-info text-white p-3">
                            <h6>Registered</h6>
                            <h3><?= $analytics['registered'] ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white p-3">
                            <h6>Pass %</h6>
                            <h3><?= $analytics['pass_percent'] ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card  bg-secondary text-white p-3">
                            <h6>Class GPA</h6>
                            <h3><?= $analytics['gpa'] ?> (<?= $analytics['gpa_grade'] ?>)</h3>
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Grade Distribution</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php foreach($grades as $grade => $count): ?>
                                <th><?= $grade ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach($grades as $count): ?>
                                <td><?= $count ?></td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">
                    Analytics not generated yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>