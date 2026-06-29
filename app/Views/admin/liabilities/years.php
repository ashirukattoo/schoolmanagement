<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><?= $pageTitle ?? "Academic Years" ?></h6>
            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addAYModal">
                <i class="fa fas-plus-circle"></i> Add New
            </button>
        </div>
        <div class="card-body">
            <table class="table datatable table-bordered">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Level</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($years as $y): ?>
                    <tr class="<?= $y['ayStatus'] === 'Current' ? 'table-success' : '' ?>">
                        <td><?= esc($y['ayName']) ?></td>
                        <td><?= esc($y['ayLevel']) ?></td>
                        <td><?= esc($y['ayStart']) ?></td>
                        <td><?= esc($y['ayEnd']) ?></td>
                        <td><?= esc($y['ayStatus']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/terms/'.$y['ayID']) ?>"
                               class="btn btn-sm btn-outline-secondary">
                                Terms
                            </a>
                            <?php if ($y['ayStatus'] !== 'Current'): ?>
                                <a href="<?= site_url('admin/academic-years/current/'.$y['ayID']) ?>"
                                   class="btn btn-sm btn-primary">
                                    Set Current
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add Academic Year Modal -->
    <div class="modal fade" id="addAYModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="<?= site_url('admin/academic-years/create') ?>" class="modal-content">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title">Add Academic Year</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Academic Year Name</label>
                        <input type="text" name="ayName" class="form-control"
                               placeholder="2024 / 2025" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select name="ayLevel" class="form-select" required>
                            <option value="O-Level">O-Level</option>
                            <option value="A-Level">A-Level</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="ayStart" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="ayEnd" class="form-control" required>
                        </div>
                    </div>

                    <input type="hidden" name="ayStatus" value="Next">
                    <input type="hidden" name="ayCreated" value="<?= date('Y-m-d H:i:s') ?>">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?= $this->endSection() ?>