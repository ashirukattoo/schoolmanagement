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
                <form action="<?= base_url('admin/results/view') ?>" method="post">
                    <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Select Examination</label>
                                <select class="form-control" name="exam" required>
                                    <?php if (!empty($exams)):
                                        foreach ($exams as $row) { ?>
                                            <option value="<?= $row['id'] ?>" class="form-control"><?= $row['year']."_".$row['term']."-term_".$row['exam']." ->".$row['class'] ; ?></option> <?php
                                        }
                                    endif; ?>
                                </select>
                            </div>
                        </div>
                        <dv class="text-end">
                            <button class="btn btn-success w-10" type="submit" name="report">Retrieve</button>
                        </dv>                    
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>