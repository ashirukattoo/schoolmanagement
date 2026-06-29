<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>

<h3>Assign Students to Route–Station</h3>
<!-- Step 1: Configuration -->
<form id="configForm">
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Class</label>
            <select name="class_id" id="class_id" class="form-control" required>
                <option value="">Select Class</option>
                <?php foreach ($classes as $cls): ?>
                    <option value="<?= $cls['cid'] ?>"><?= $cls['named'] ?></option>
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
            <label>Station</label>
            <select name="station_id" id="station_id" class="form-control" required>
                <option value="">Select Station</option>
            </select>
        </div>
    </div>
    <button type="button" id="loadStudents" class="btn btn-primary">Load Students</button>
</form>
<hr>
<!-- Step 2: Student List -->
<form action="<?= base_url('students/storeBulk') ?>" method="post" id="assignForm" style="display:none;">
    <input type="hidden" name="route_id" id="hidden_route">
    <input type="hidden" name="station_id" id="hidden_station">

    <h5>Students in Selected Class</h5>
    <div id="studentsContainer" class="mb-3"></div>

    <div class="mb-3">
        <label>Assigned By</label>
        <select name="assigned_by" class="form-control">
            <option value="">Select Employee</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= $emp['empID'] ?>"><?= $emp['empFname'].' '.$emp['empSurname'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-success" type="submit">Assign Selected</button>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
    // Fetch stations by route
    $('#route_id').change(function() {
        const routeID = $(this).val();
        if (routeID) {
            $.get('<?= base_url('trans/getStationsByRoute') ?>/' + routeID, function(data) {
                $('#station_id').html(data);
            });
        } else {
            $('#station_id').html('<option value="">No Station belong</option>');
        }
    });

    // Load students dynamically by class
    $('#loadStudents').click(function() {
        const classID = $('#class_id').val();
        const routeID = $('#route_id').val();
        const stationID = $('#station_id').val();

        if (!classID || !routeID || !stationID) {
            alert('Please select class, route and station first.');
            return;
        }

        $.get('<?= base_url('trans/getStudentsByClass') ?>/' + classID, function(data) {
            $('#studentsContainer').html(data);
            $('#assignForm').show();
            $('#hidden_route').val(routeID);
            $('#hidden_station').val(stationID);
        });
    });
});
</script>

<?= $this->endSection() ?>