<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>

<h4>Step 1: Configuration</h4>
<?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
<form id="configForm">
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Class</label>
            <select name="class_id" id="class_id" class="form-control" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['cid'] ?>"><?= $c['named'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Route</label>
            <select name="route_id" id="route_id" class="form-control" required>
                <option value="">Select Route</option>
                <?php foreach ($routes as $r): ?>
                    <option value="<?= $r['rouID'] ?>"><?= $r['rouName'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>&nbsp;</label>
            <button type="button" id="fetchDataBtn" class="btn btn-primary w-100">Next</button>
        </div>
    </div>
</form>

<div id="assignmentForm" style="display:none;">
    <h4>Step 2: Assign Students to Stations</h4>
    <form action="<?= base_url('assignments/save') ?>" method="post">
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

<!--<script>
$('#fetchDataBtn').on('click', function() {
    let class_id = $('#class_id').val();
    let route_id = $('#route_id').val();

    if (!class_id || !route_id) {
        alert('Please select both Class and Route');
        return;
    }

    $.post('<?= base_url('assignments/fetchData') ?>', { class_id, route_id }, function(response) {
        let students = response.students;
        let stations = response.stations;

        if (students.length === 0) {
            alert('No students found for this class.');
            return;
        }

        let stationOptions = stations.map(s => `<option value="${s.rsID}">${s.staName}</option>`).join('');

        let rows = '';
        students.forEach(stu => {
            rows += `
                <tr>
                    <td>${stu.stuFname} ${stu.stuSurname}
                        <input type="hidden" name="assignments[][student_id]" value="${stu.stuID}">
                    </td>
                    <td>
                        <select name="assignments[][route_station_id]" class="form-control">
                            ${stationOptions}
                        </select>
                    </td>
                </tr>`;
        });

        $('#studentRows').html(rows);
        $('#assignmentForm').show();
    }, 'json');
});
</script> -->
<script>
$('#fetchDataBtn').on('click', function() {
    let class_id = $('#class_id').val();
    let route_id = $('#route_id').val();

    if (!class_id || !route_id) {
        alert('Please select both Class and Route');
        return;
    }

    console.log("Sending data:", {class_id, route_id});

    $.ajax({
        url: '<?= base_url('assignments/fetchData') ?>',
        type: 'POST',
        dataType: 'json',
        data: { class_id, route_id },
        success: function(response) {
            console.log("Response received:", response);

            if (!response.students || response.students.length === 0) {
                alert('No students found for this class.');
                return;
            }

            let stationOptions = '<option value="">-- Select Station --</option>';
            stationOptions += response.stations.map(s =>
                    `<option value="${s.rsID}">${s.staName}</option>`
            ).join('');

            let rows = '';
            response.students.forEach(stu => {
                rows += `
                    <tr>
                        <td>${stu.stuFname} ${stu.stuSurname}
                            <input type="hidden" name="assignments[][student_id]" value="${stu.stuID}">
                        </td>
                        <td>
                            <select name="assignments[][route_station_id]" class="form-control">
                                ${stationOptions}
                            </select>
                        </td>
                    </tr>`;
            });

            $('#studentRows').html(rows);
            $('#assignmentForm').fadeIn();
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            console.log(xhr.responseText);
            alert('Failed to fetch data. Check console.');
        }
    });
});
</script>


<?= $this->endSection() ?>