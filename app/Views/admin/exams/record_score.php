<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-1">
    <div class="container mt-4">
        
        <!-- Upload Section -->
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-info text-white">
                <h5 class="fw-bold"><?= esc($pageTitle) ?></h5>
            </div>
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
                <form action="<?= base_url('admin/exams/upload/') ?>" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Select Exam</label>
                            <select class="form-control" name="exam" required>
                                <option value="">--Select Exam--</option>
                                <?php foreach ($exams as $exam): ?>
                                    <option value="<?= $exam['id'] ?>"><?= esc($exam['exam'].' - '.$exam['class']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Upload Score Sheet (Excel/CSV)</label>
                            <input type="file" name="sheet" class="form-control" accept=".xlsx,.csv" required>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-4">
                                <label> </label>
                                <button type="submit" class="btn btn-success">Upload Results</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>