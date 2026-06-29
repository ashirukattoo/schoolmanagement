<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white"><h6 class="mb-0"><?= $pageTitle ?></h6></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url('admin/add/class') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="class" class="form-control" placeholder="E.g: Form One" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Short</label>
                            <input type="text" name="short" class="form-control" placeholder="E.g: FI" required>
                        </div>                       
                        <div class="col-md-3 mb-3">
                            <label>Numeral</label>
                            <input type="number" name="numeral" class="form-control" required min="1" max="20">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Class</label>
                            <select name="level" class="form-control" required>
                                <option value="1">O-Level</option>
                                <option value="2">A-Level</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10 mx-auto text-white">Add</button>                
                </form>
            </div>
        </div>
        <div class="card mt-4">
        <div class="card-header bg-info text-white">   
            <h6>List of Classes</h6>
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