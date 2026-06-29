<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
    <div class="container mt-2 p-0">
        <div class="card">
            <div class="card-header bg-info text-white p-2"><h4 class="text-left">Student Report Card</h4>
            </div>
            <div class="card-body">        
                <table class="table table-bordered">
                    <thead>
                        <th colspan="4" style="text-align: center">Student Info</th>
                    </thead>
                    <tr>
                        <th>Student</th>
                        <td><?= ucwords($student['name']) ?></td>
                        <th>Class</th>
                        <td><?= $class['named'] ?></td>
                    </tr>
                    <tr>
                        <th>Stream/Combination</th>
                        <td><?= $student['stream'] ?></td>
                        <th>Exam</th>
                        <td><?= $exam['exName'] ?></td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-md-7 m-0">                        
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="4" style="text-align: center">Subject Performance</th>
                                </tr>
                                <tr>
                                    <th>Subject</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($decoded['subjects'] as $sub): ?>
                                    <tr>
                                        <td><?= $sub['subject'] ?></td>
                                        <td><?= $sub['score'] ?></td>
                                        <td><?= $sub['grade'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5 m-0">
                        <table class="table table-bordered">
                            <tr>
                                <tr>
                                    <th colspan="4" style="text-align: center">Summary</th>
                                </tr>
                                <th>Total Points</th>
                                <td><?= $decoded['points'] ?></td>
                                <th>Division</th>
                                <td><?= $decoded['division'] ?></td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td ><?= $decoded['remark'] ?></td>
                                <td colspan="2"><a href="<?= base_url("admin/report_card/pdf/$exam_id/$student_id") ?>" class="btn btn-primary">
                            Download Report
                        </a></td>
                            </tr>
                        </table>
                        
                    </div> 
                </div>   
            </div>
        </div>
<?= $this->endSection(); ?>