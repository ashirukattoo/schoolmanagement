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
                <a href="<?= base_url('admin/template/subject') ?>" class="btn btn-success mb-3">
                    <i class="fa fa-download"></i> Download Template
                </a>

                <form action="<?= base_url('admin/import/subject') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-12">
                            <h5>Bulk Upload</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-info w-10 mx-auto text-white">Upload</button>
                        </div>
                    </div>
                </form>
                <form action="<?= base_url('admin/save/subject') ?>" method="post">
                    <div class="row">
                        <div class="col-md-12 mb-12">
                            <h5>Add Single Subject</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="E.g Geography" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option>Core</option>
                                <option>Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Abreviation</label>
                            <input type="text" name="short" placeholder="E.g Geog" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Level</label>
                            <select name="level" class="form-control">
                                <option value="O-Level">O-level</option>
                                <option value="A-Level">A-Level</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Curriculum</label>
                            <select name="curriculum" class="form-control">
                                <option>Old</option>
                                <option>New</option>
                                <option>Both</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10 mx-auto text-white">Add</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>