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
                <form action="<?= base_url('admin/report/stream') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="1">Class</option>
                                <option value="2">Streams</option>
                                <option value="3">Subjects</option>
                                <option value="4">Lessons</option>
                                <option value="5">Misceleneous</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Class</label>
                            <select name="category" class="form-control" required>
                                <option>-- Select Class --</option>
                                <option value="1">Form I</option>
                                <option value="2">Form II</option>
                                <option value="3">Form III</option>
                                <option value="4">Form IV</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Stream</label>
                            <select name="category" class="form-control" >
                                <option>-- Select Stream --</option>
                                <?php foreach ($streams as $s):  ?>
                                <option value="<?= $s['sid'] ?>"><?= $s['class'].'-'.$s['sName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10 mx-auto text-white">Add</button>                
                </form>
            </div>
        </div>
        <div class="card mt-4">
        <div class="card-header bg-info text-white">   
            <h6 id="reportHeading"></h6>
        </div>
        <div class="card-body">
        </div>
    </div>
<?= $this->endSection() ?>