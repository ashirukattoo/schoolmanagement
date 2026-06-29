<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-1">
    <div class="card  card-outline-info">
        <div class="card-header bg-success text-center align-content-center text-light p-2 ">   
            <h5><?= strtoupper($info['exam'].' - '.$info['class'].' '.$info['year']); ?></h5>        
        </div>
        <div class="card-body">
            <div class="card card-outline-info">
                <div class="card-header p-0">
                    <h4> DIVISION PERFORMANCE SUMMARY 
                    <a href="<?= base_url('admin/print_analytice/'.$exam_id) ?>"
                       class="btn btn-outline-success no-print">
                       <i class="fas fa-print"></i>
                    </a></h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SEX</th>
                                <th>I</th>
                                <th>II</th>
                                <th>III</th>
                                <th>IV</th>
                                <th>0</th>
                                <th>Inc</th>
                                <td><strong>TOTAL</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($general_results as $sex => $divisions): ?>
                                <tr>
                                    <td><?= $sex ?></td>
                                    <td><?= $divisions['I'] ?></td>
                                    <td><?= $divisions['II'] ?></td>
                                    <td><?= $divisions['III'] ?></td>
                                    <td><?= $divisions['IV'] ?></td>
                                    <td><?= $divisions['0'] ?></td>
                                    <td><?= $divisions['Inc'] ?></td>
                                    <td><strong><?= $divisions['TOTAL'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td>GRAND TOTAL</td>
                                <td><?= $grand_total['I'] ?></td>
                                <td><?= $grand_total['II'] ?></td>
                                <td><?= $grand_total['III'] ?></td>
                                <td><?= $grand_total['IV'] ?></td>
                                <td><?= $grand_total['0'] ?></td>
                                <td><?= $grand_total['Inc'] ?></td>
                                <td><?= $grand_total['TOTAL'] ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>

                <?php 
                    $allGrades = [];
                    foreach ($subjects as $row) {
                        if (!empty($row['grades_json'])) {
                            foreach($row['grades'] as $grade =>$count){
                                if (!in_array($grade, $allGrades)) {
                                    $allGrades[] =$grade;
                                }
                            }
                        }
                    }
                    $gradeOrder = ['A','B','C','D','E','S','F'];

                    // Sort based on this order
                    usort($allGrades, function($a, $b) use ($gradeOrder) {
                        return array_search($a, $gradeOrder) - array_search($b, $gradeOrder);
                    });
                ?>            
            <div class="card card-outline-primary">
                <div class="card-header p-0">
                    <h4> SUBJECTS PERFORMANCE</h4>
                </div>
                <div class="body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>SEAT</th>

                                <?php foreach($allGrades as $g): ?>
                                    <th><?= $g ?></th>
                                <?php endforeach; ?>

                                <th>PASS</th>
                                <th>PASS%</th>
                                <th>GPA</th>
                                <th>GRADE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($subjects as $row): ?>
                            <tr >
                                <td><?= $row['subName'] ?></td>
                                <td><?= $row['seat'] ?></td>

                                <?php foreach($allGrades as $g): ?>
                                    <td ><?= (int)($row['grades'][$g] ?? 0) ?></td>
                                <?php endforeach; ?>

                                <td><?= $row['pass'] ?></td>
                                <td><?= number_format($row['pass_percent'],1) ?>%</td>
                                <td><?= number_format($row['gpa'],4) ?></td>
                                <td><?= $row['grade'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>