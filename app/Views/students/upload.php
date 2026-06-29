<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p-4 shadow-sm">
            <div class="card-header"><h3 class="text-center mb-0"><i class="fas fa-arrow-up"></i> <?= $pageTitle ?></h3></div>
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
                <form action="<?= base_url('students/import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="file" name="file" accept=".cvs, .xlsx" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-success w-25 mx-auto">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>