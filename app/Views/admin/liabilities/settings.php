<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-0">
        <div class="card">
        <div class="card-header bg-info text-white">   
            <h6><?= $pageTitle ?? "Setting" ?></h6>
        </div>
        <div class="card-body">
            <form method="post" action="<?= site_url('admin/terms/create') ?>" class="card p-3 mb-4">
                <?= csrf_field() ?>
                <input type="hidden" name="year" value="<?= $ayID ?>">

                <div class="row">
                    <div class="col-md-4">
                        <input name="name" class="form-control" placeholder="Term name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Add</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Term</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($terms as $t): ?>
                    <tr>
                        <td><?= esc($t['tName']) ?></td>
                        <td><?= $t['tStart'] ?> → <?= $t['tEnd'] ?></td>
                        <td>
                            <span class="badge bg-<?= $t['tStatus']=='Current'?'success':'secondary' ?>">
                                <?= ucfirst($t['tStatus']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($t['tStatus'] != 'Current' && ($t['tStatus'] && 'Previous' && $t['tStatus'] !='Old')): ?>
                                <a href="<?= base_url('admin/terms/activate/'.$t['tID']) ?>"
                                   class="btn btn-sm btn-success">
                                   Activate
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>