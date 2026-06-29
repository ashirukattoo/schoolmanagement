<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info text-white p-2"><span class="mb-0"><?= $pageTitle ?></span></div>
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
                <form action="" method="post">
                    <?= csrf_field() ?>
                        <div class="row">
                            <input type="hidden" name="class" value="<?= $class ?>" >
                            <input type="hidden" name="class" value="<?= $exam ?>" >
                            <div class="col-md-4 mb-3">
                                <label>Clossing Date</label>
                                <input type="date" name="openDate" value="<?= date('Y-m-d') ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Openning Date</label>
                                <input type="date" name="closeDate" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Head of School's Instructions</label>
                                <textarea class="form-control"  name="mapendekezo" rows="5"></textarea>
                            </div>
                        </div>
                        <dv class="text-end">
                            <button class="btn btn-success w-10" type="submit" name="report">Generate</button>
                        </dv>                    
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>