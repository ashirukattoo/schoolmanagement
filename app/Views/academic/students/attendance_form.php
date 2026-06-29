<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white">   
            <h4><?= $pageTitle; ?></h4>
        </div>
        <div class="card-body">
            <h2>Take Attendance</h2>
            <?php if(session()->getFlashdata('success')): ?>
                <p style="color:green"><?= session()->getFlashdata('success') ?></p>
            <?php endif; ?>
            <a class="btn btn-primary" href="<?= base_url('admin/students/attendance/save') ?>"><i class="fas fa-bar-graph"></i> History</a>

            <form method="post" action="<?= base_url('admin/students/attendance/save') ?>">
                <input type="hidden" name="stream_id" value="<?= $stream_id ?>">
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Term:</label>
                        <input class="form-control" type="text" name="term_id" placeholder="e.g. 2026-T1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Session:</label>
                        <select name="session" class="form-control">
                            <option value="Morning">Morning</option>
                            <option value="Noon">Noon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class=" table table-bordered table-striped table-hover">
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                        </tr>
                        <?php foreach($students as $student): ?>
                        <tr>
                            <td><?= $student['name'] ?></td>
                            <td>
                                <select class="form-control" name="students[<?= $student['id'] ?>]">
                                    <option value="I" style="background: lightgreen; color: darkgreen;">Present</option>
                                    <option value="A">Absent</option>
                                    <option value="S">Sick</option>
                                    <option value="P">Permited</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <button class="btn btn-outline-success align-right" type="submit"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>