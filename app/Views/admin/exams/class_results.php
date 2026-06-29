<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>

<div class="container mt-2">
    <div class="d-flex justify-content align-items-center mb-1">
        <h4 class="me-5"><?= esc($pageTitle) ?></h4>
        <button id="toggleMode" class="btn btn-outline-primary btn-sm me-1">Grade Mode</button>
        <a href="<?= base_url('admin/export/excel/'.$exam['exID'].'/'.$class['cid']) ?>" class="btn btn-outline-success btn-sm me-1"><i class="fas fa-file-excel"></i> Export excel</a>
        <a href="<?= base_url('admin/export/pdf/'.$class['cid'].'/'.$exam['exID']) ?>" class="btn btn-outline-danger btn-sm me-1"><i class="fas fa-file-pdf"></i> Export pdf</a>
    </div>

    <table id="resultsTable" class="table datatable table-bordered table-striped table-sm">
        <thead class="table-info">
            <tr>
                <th>#</th>
                <th style="text-align: justify;">Student</th>
                <?php foreach ($subjects as $sub): ?>
                    <th><?= esc(substr($sub, 0, 4)) ?></th>
                <?php endforeach; ?>
                <th class="score-mode">TOTAL</th>
                <th class="score-mode">AVRG</th>
                <th class="grade-mode d-none">POINT</th>
                <th class="grade-mode d-none">DIV</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach ($results as $row): 
                $decoded = $row['decoded'];
                $subjectData = array_column($decoded['subjects'], null, 'subject');
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= esc($row['student']) ?></td>
                <?php foreach ($subjects as $sub): ?>
                    <?php if (isset($subjectData[$sub])): 
                        if (is_numeric($subjectData[$sub]['score'])) {
                            $sc = number_format($subjectData[$sub]['score'], 0);
                        }else{
                            $sc = $subjectData[$sub]['score'];
                        }                ?>
                        <td data-score="<?= esc($sc) ?>" data-grade="<?= esc($subjectData[$sub]['grade']) ?>">
                            <?= esc($sc) ?>
                        </td>
                    <?php else: ?>
                        <td>-</td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td class="score-mode"><?= esc($decoded['total']) ?? 0?></td>
                <td class="score-mode"><?= esc($row['crAverage']) ?? 0?></td>
                <td class="grade-mode d-none"><?= esc($decoded['points'] ?? 0) ?></td>
                <td class="grade-mode d-none"><?= esc($decoded['division']) ?? 0 ?></td>
                <td><?= esc($decoded['remark']) ?></td>
                <td>
                    <a href="<?= base_url('admin/report_card/'.$exam['exID'].'/'.$row['student_id']) ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("toggleMode");
    let isGradeMode = false;

    toggleBtn.addEventListener("click", () => {
        const scoreCells = document.querySelectorAll("[data-score]");
        scoreCells.forEach(cell => {
            const score = cell.dataset.score;
            const grade = cell.dataset.grade;
            cell.textContent = isGradeMode ? score : grade;
        });

        document.querySelectorAll(".score-mode").forEach(el => el.classList.toggle("d-none"));
        document.querySelectorAll(".grade-mode").forEach(el => el.classList.toggle("d-none"));

        isGradeMode = !isGradeMode;
        toggleBtn.textContent = isGradeMode ? "Score Mode" : "Grade Mode";
    });
});
</script>

<style>
.table th, .table td { text-align: center; vertical-align: middle; }
</style>

<?= $this->endSection(); ?>