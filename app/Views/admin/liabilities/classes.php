<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
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
                        <th>Class</th>
                        <th>Level</th>                  
                        <th>Numeral</th>
                        <th>Created</th>
                        <th>last Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($classes)):
                    $count=1;
                 ?>
                    <?php foreach ($classes as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['named'] ?></td>
                            <td><?php if ($s['level'] == 1) {
                                echo 'O-Level';
                            }else{
                                echo 'A-level';
                            } ?></td>
                            <td><?= $s['numeral'] ?></td>
                            <td><?= $s['created'] ?></td>
                            <td><?= $s['updated'] ?></td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="6">No Class found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>