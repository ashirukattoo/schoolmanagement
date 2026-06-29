<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info"><h6 class="mb-0"><?= $pageTitle ?></h6></div>
            <div class="card-body">
                <div class="text-end d-flex justify-content-between align-items-right mb-3">
                    <button id="addExam" class="btn btn-outline-info "><i class="fas fa-file-excel"></i> Assign Station</button>
                </div>
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url('admin/trans/stations/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Route Name</label>
                            <input type="text" name="route" class="form-control" value="<?= $route['name'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Start Region</label>
                            <select name="region" class="form-control">
                                <?php if(!empty($regions)): ?>?
                                    <?php foreach ($regions as $k) {
                                        if ($route['start']===$k['regName']) { ?>
                                             <option selected value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                         <?php }else{ ?>
                                       <option value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                    <?php } }?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Destination Region</label>
                            <select name="district" class="form-control">
                                <?php if(!empty($regions)): ?>?
                                    <?php foreach ($regions as $k) {
                                        if ($route['end']===$k['regName']) { ?>
                                             <option selected value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                         <?php }else{ ?>
                                       <option value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                    <?php } }?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-info w-10">Update</button>
                </form>
            </div>
        </div>
        <div class="card mx-auto shadow-sm mt-1">
            <div class="card-header bg-info"><h6 class="mb-0">Assinned Station(s) on "<?= $route['name'] ?>" Route</h6></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('delete')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('delete') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('undelete')) { ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('undelete') ?>
                    </div>
                <?php } ?>
                <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Station</th>
                            <th>Ward</th>
                            <th>District</th>
                            <th>Region</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($assignedStations)):
                        $count=1;
                     ?>
                        <?php foreach ($assignedStations as $s): ?>
                            <tr>
                                <td><?= $count; ?></td>
                                <td><?= $s['station'] ?></td>
                                <td><?= $s['street'].' - '.$s['ward'] ?></td>
                                <td><?= $s['district'] ?? 'NIL' ?></td>
                                <td><?= $s['region'] ?? 'NIL' ?></td>
                                <td>
                                    <a href="?view_id=<?= $s['id'] ?>" class="btn btn-secondary"><i class="fas fa-eye"></i></a>
                                    <a href="<?= base_url('admin/trans/route/edit/'.$s['id']); ?>" class="btn btn-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="<?= base_url('trans/route') ?>?del_id=<?= $s['id'] ?>" class="btn btn-secondary"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="6">No Station Assigned Yet</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="examModel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Add New Station to <?= $route['name'] ?></h5>
                    <button class="btn-close" id="btndismiss" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignStationForm" action="<?= base_url('admin/trans/route/add/station/'.$route['id']) ?>" method="post" />
                        <div class="row">
                            <div class="form-group">
                                <label>Station</label>
                                <select class="form-control" name="station" required >
                                    <option>--Select Station--</option>
                                    <?php foreach ($recStations as $key => $row) { ?>
                                       <option value="<?= $row['staID'] ?>"><?= $row['staName'] ?></option>
                                    <?php }  ?>
                                </select>
                                <input type="hidden" name="route" value="<?= $route['id'] ?>">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="text-end">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>