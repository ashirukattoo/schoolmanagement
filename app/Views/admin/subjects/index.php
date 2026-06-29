<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white">   
            <h6><?= $pageTitle ?></h6>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Category</th>
                        <th>Curriculum</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($subjects)):
                    $count=1;
                 ?>
                    <?php foreach ($subjects as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['subCode'] ?></td>
                            <td><?= $s['subName'] ?></td>
                            <td><?= $s['subLevel'] ?? 'NIL' ?></td>
                            <td><?= $s['subCategory'] ?></td>
                            <td><?= $s['subCurriculum'] ?></td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="6">No Record found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>