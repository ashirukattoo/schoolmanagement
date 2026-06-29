<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p-4 shadow-sm">
            <div class="card-header"><h3 class="text-center mb-0">🧾 <?= $pageTitle ?></h3></div>
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
                <form action="<?= base_url('trans/stations/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Station Name</label>
                            <input type="text" name="station" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Region</label>
                            <select name="region" class="form-control">
                                <?php if(!empty($regions)): ?>?
                                    <?php foreach ($regions as $k) { ?>
                                       <option value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                    <?php } ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Disctric</label>
                            <select name="district" class="form-control">
                                <?php if(!empty($districts)): ?>?
                                    <?php foreach ($districts as $k) { ?>
                                       <option value="<?= $k['disID'] ?>"><?= $k['disName'] ?></option>
                                    <?php } ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Ward</label>
                            <select name="ward" class="form-control">
                                <option>--Select Ward--</option>
                                <?php if(!empty($wards)): ?>?
                                    <?php foreach ($wards as $k) { ?>
                                       <option value="<?= $k['waID'] ?>"><?= $k['waName'] ?></option>
                                    <?php } ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Street</label>
                            <select name="street" class="form-control">
                                <option>--Select Street--</option>
                                <?php if(!empty($streets)): ?>?
                                    <?php foreach ($streets as $k) { ?>
                                       <option value="<?= $k['strID'] ?>"><?= $k['strName'] ?></option>
                                    <?php } ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success w-25">Register</button>
                </form>
            </div>
        </div>
        <div class="card mx-auto mt-3 p-4 shadow-sm">
            <div class="card-header"><h3 class="text-center mb-0">Recoreded Stations</h3></div>
            <div class="card-body">
                <table id="dataTable" class="table datatable table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Staion Name</th>
                            <th>Ward</th>
                            <th>District</th>
                            <th>Region</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($recStations)):
                        $count=1;
                     ?>
                        <?php foreach ($recStations as $s): ?>
                            <tr>
                                <td><?= $count; ?></td>
                                <td><?= $s['staName'] ?></td>
                                <td><?= $s['street'].' - '.$s['ward'] ?></td>
                                <td><?= $s['district'] ?></td>
                                <td><?= $s['region']?></td>
                                <td><?= $s['staStatus'] ?? 0?></td>
                            </tr>
                        <?php
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="6">No record found.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>