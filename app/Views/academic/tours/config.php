<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>

<div class="card mx-auto p-4 shadow-sm">
    <div class="card-header bg-info text-white">
        <span>Student Station Assignment</span>
    </div>
    <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif ?>

        <!-- Configuration Form -->
        <form id="configForm" class="mb-4">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Class</label>
                    <select name="class_id" id="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['cid'] ?>"><?= $c['named'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Route</label>
                    <select name="route_id" id="route_id" class="form-control" required>
                        <option value="">Select Route</option>
                        <?php foreach ($routes as $r): ?>
                            <option value="<?= $r['rouID'] ?>"><?= $r['rouName'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tour</label>
                    <select name="tour_id" id="tour_id" class="form-control" required>
                        <option value="">Select Tour</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['touID'] ?>"><?= $t['touName'].' -:- '.$t['touCategory'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" id="fetchDataBtn" class="btn btn-primary w-100">Fetch Students</button>
                </div>
            </div>
        </form>

        <!-- Assignment Form -->
        <div id="assignmentForm" class="card" style="display:none;">
            <div class="card-header bg-info text-white">
                <h6>Assign Students to Stations</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/trans/assignments/save') ?>" method="post">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Station</th>
                            </tr>
                        </thead>
                        <tbody id="studentRows"></tbody>
                    </table>
                    <button class="btn btn-success">Save Assignments</button>
                </form>
            </div>
        </div>

        <!-- Optional: Assigned Students Report -->
        <div id="assignedReport" class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h6>Assigned Students Report</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="reportTable">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Route</th>
                            <th>Station</th>
                            <th>Tour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rendered dynamically if you pass $assignedStudents from controller -->
                        <?php if(!empty($assignedStudents)): ?>
                            <?php foreach($assignedStudents as $stu): ?>
                                <tr>
                                    <td><?= $stu['name'] ?></td>
                                    <td><?= $stu['class'] ?></td>
                                    <td><?= $stu['route'] ?></td>
                                    <td><?= $stu['station'] ?></td>
                                    <td><?= $stu['tour'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No assignments yet</td></tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
$('#fetchDataBtn').click(function(){
    let class_id = $('#class_id').val();
    let route_id = $('#route_id').val();
    let tour_id  = $('#tour_id').val();

    if(!class_id || !route_id || !tour_id){
        alert('Select Class, Route and Tour first');
        return;
    }

    $.post('<?= base_url('admin/trans/assignments/fetchData') ?>',
        {class_id, route_id, tour_id},
        function(res){
            if(!res.students.length){
                alert('No students found or all already assigned');
                $('#assignmentForm').hide();
                return;
            }

            let rows = '';
            res.students.forEach(stu=>{
                let options = res.stations.map(s=>`<option value="${s.rsID}">${s.staName}</option>`).join('');
                rows += `<tr>
                    <td>${stu.name}<input type="hidden" name="assignments[][student_id]" value="${stu.stuID}"></td>
                    <td><select name="assignments[][route_station_id]" class="form-control">${options}</select></td>
                </tr>`;
            });
            $('#studentRows').html(rows);
            $('#assignmentForm').fadeIn();
        }, 'json');
});
</script>

<?= $this->endSection() ?>
