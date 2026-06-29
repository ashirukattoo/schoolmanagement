<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white"> <h6>Fetch Specific Data</h6>  </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('students/assignRoute') ?>">
                <div class="row">
                    <div class="form-group col-sm-5">
                        <select class="form-control" name="class" required>
                            <option> -- Select Class</option>
                            <?php foreach ($classes as $s) { ?>
                                <option value="<?= $s['cid'] ?>"><?= $s['named'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3 "><input type="submit" name="assignStudent" value="Fetch" class="btn btn-info"></div>
                    
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">   
            <h4><?= $pageTitle ?></h4>
        </div>
        <div class="card-body">
            <table id="dataTable" class="table datatable  table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Route Station</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($assignedStudents)):
                    $count=1;
                 ?>
                    <?php foreach ($assignedStudents as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= $s['stuFname']." ".$s['stuMname']." ".$s['stuSurname'] ?></td>
                            <td><?= $s['class'] ?></td>
                            <td><?= $s['rouName'] ?? 'NIL' ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="<?= base_url('admin/trans/stations/students/assign_route/station/'.$s['stuID']) ?>"><i class="fas fa-map"></i></a>
                                <a class="btn btn-sm btn-info" href="<?= base_url('admin/trans/stations/students/assign_route/bus/'.$s['stuID']) ?>"><i class="fas fa-bus"></i></a>
                                <a class="btn btn-sm btn-danger" href="<?= base_url('admin/trans/stations/students/assign_route/delete/'.$s['stuID']) ?>" onclick="return confirm('Real Do want to Cancel this Assignment?')"><i class="fas fa-times"></i></a>
                            </td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="5">No students found.</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>