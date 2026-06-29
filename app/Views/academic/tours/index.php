<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content'); ?>
<div class="container mt-1">
    <div class="card">
        <div class="card-header text-info">   
            <h5>Students Arrangement on Stations
            <a class="btn btn-sm btn-success  me-2" href="<?= base_url('admin/trans/config/students'); ?>">Assign Student</a>
            <button id="addExam" class="btn btn-sm btn-outline-info "><i class="fas fa-map"></i> Add Tour</button>
            <button id="tourAddVehicle" class="btn btn-sm btn-outline-primary "><i class="fas fa-bus"></i> Assign Tour on Route</button>
        </h5>
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
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Route <strong>Station</strong></th>
                        <th>Assigned By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($studentRoutes)):
                    $count=1;
                 ?>
                    <?php foreach ($studentRoutes as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['stuFname']." ".$s['stuMname']." ".$s['stuSurname'] ?></td>
                            <td><?= $s['rouName'].' <strong>('.$s['staName'].')</strong>'; ?></td>
                            <td><?= $s['empFname'].' '.$s['empSurname'] ?></td>
                            <td><?= $s['status'] ?? 0?></td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No record found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-1">
        <div class="card-header bg-info text-white">   
            <h5 class="pt-0">Vehicles Vs Employment Assignments</h5>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tour</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Employee</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($tourRoutes)):
                    $count=1;
                 ?>
                    <?php foreach ($tourRoutes as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['touName'] ?></td>
                            <td><?= $s['rouName'] ?></td>
                            <td><?= $s['vePlateNumber'] ?? 'NIL' ?></td>
                            <td><?= $s['first_name'].' '.$s['surname'] ?></td>
                            <td><?= $s['trDeparture'] ?? 'Not Departured' ?></td>
                            <td><?= $s['trArrival'] ?? 'Not Arrived' ?></td>
                            <td><?= $s['trStatus'] ?? 0?></td>
                            <td>
                                <?php if(strtolower($s['trStatus']) ==='coming'): ?>
                                    <a href="" class="btn btn-sm btn-primary">Departure</a>
                                <?php elseif(strtolower($s['trStatus']) ==='active'): ?>
                                    <a href="" class="btn btn-sm btn-success">Arrive</a>
                                <?php else: ?>
                                   <a href="" class="btn btn-sm btn-danger">Cancel</a>
                                <?php endif; ?> 
                            </td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="8">No Record found.</td></tr>
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
                <h5 class="modal-title">Add New Tour</h5>
                <button class="btn-close" id="btndismiss" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="examfForm" action="<?= base_url('/admin/trans/tours/add') ?>" method="post" />
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Tour Name</label>
                            <input type="text" name="tour" class="form-control" placeholder="E.g: December Holiday" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option>-- Select Category --</option>
                                <option value="Departure"> Departure </option>
                                <option value="Arrival"> Arrival </option>
                                <option value="Tour"> Tour </option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3" >
                            <label>Start Date</label>
                            <input type="date" name="start" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3" >
                            <label>End Date</label>
                            <input type="date" name="end" class="form-control" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="text-end">
                        <button class="btn btn-info w-10">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="assignTourOnRouteModel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">New Assignment of Tour On Route</h5>
                <button class="btn-close" id="btndismiss" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="tourRouteForm" action="<?= base_url('admin/trans/tours/assign') ?>" method="post" />
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Tour Name</label>
                            <select name="tour" class="form-control">
                                <option> -- Select Tour -- </option>
                                <?php foreach($tours as $t): ?>
                                    <option value="<?= $t['touID'] ?>"><?= $t['touName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Route Name</label>
                            <select name="route" class="form-control">
                                <option> -- Select Route -- </option>
                                <?php foreach($routes as $t): ?>
                                    <option value="<?= $t['rouID'] ?>"><?= $t['rouName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Employee</label>
                            <select name="employee" class="form-control">
                                <option> -- Select Employee -- </option>
                                <?php foreach($employees as $t): ?>
                                    <option value="<?= $t['empID'] ?>"><?= $t['empFname'].' '.$t['empSurname'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Vehicles</label>
                            <select name="vehicle" class="form-control">
                                <option> -- Select Vehicle -- </option>
                                <?php foreach($vehicles as $t): ?>
                                    <option value="<?= $t['veID'] ?>"><?= $t['vePlateNumber'].' '.$t['veNamed'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="text-end">
                        <button class="btn btn-info w-10">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>