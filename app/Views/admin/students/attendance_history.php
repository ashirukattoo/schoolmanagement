<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white">   
            <h4><?= $pageTitle; ?></h4>
        </div>
        <div class="card-body">
            <h3>Monthly Attendance</h3>
            <table>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>

                    <?php for($d=1; $d<=31; $d++): ?>
                        <th><?= $d ?></th>
                    <?php endfor; ?>
                </tr>

                <?php $i=1; foreach($students as $student): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td style="text-align:left"><?= $student['name'] ?></td>

                    <?php for($d=1; $d<=31; $d++): ?>

                        <?php
                            $status = $attendanceMap[$student['id']][$d] ?? '';
                        ?>

                        <td>
                            <?= $status ?>
                        </td>

                    <?php endfor; ?>

                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>