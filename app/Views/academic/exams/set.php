<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>

<div class="container mt-1">
    <div class="card card-outline-info">
        <div class="card-header bg-white text-info">   
            <h6><?= $pageTitle ?></h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <button id="addExam" class="btn btn-outline-info"><i class="fas fa-file-excel"></i> Add Exam</button>
                <button id="addAcademicYear" class="btn btn-outline-success m-3"><i class="fas fa-calendar-plus"></i> Add Academic Year</button>
            </div>
        </div>
    </div>
</div>

<!-- Exam Modal -->
<div class="modal fade" id="examModel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Add New Exam</h5>
                <button class="btn-close" id="btndismiss" type="button" data-bs-dismiss="modal"></button>
            </div>

            <form id="examForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label>Examination Name</label>
                            <input class="form-control" type="text" name="name" placeholder="E.g: Annual Examination" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Academic Year</label>
                            <select class="form-control" name="year" id="academicYearSelect" required>
                                <option value="">--Select Academic Year--</option>
                                <?php foreach ($years as $y): ?>
                                    <option value="<?= $y['ayID'] ?>"><?= esc($y['ayName'].'-'.$y['ayLevel']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Class</label>
                            <select class="form-control" name="class" id="classSelect" required>
                                <option value="">--Select Class--</option>
                                <?php foreach ($classes as $row): ?>
                                    <option value="<?= $row['cid'] ?>"><?= esc($row['named']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mt-3">
                            <h5 class="mb-2 text-info">Subjects</h5>
                            <div id="subjectListOption" class="border rounded p-2 bg-light">
                                <p class="text-muted">Select a class above to load subjects...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Exam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Academic Year Modal -->
<div class="modal fade" id="ayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add New Academic Year</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>

            <form id="academicYear">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Year</label>
                        <input class="form-control" type="text" name="name" placeholder="E.g: 2025 or 2025/2026" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Level</label>
                        <select class="form-control" name="level" required>
                            <option value="">--Select Level--</option>
                            <option value="O-Level">O-Level</option>
                            <option value="A-Level">A-Level</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label>Start</label>
                        <input class="form-control" type="date" name="start">
                    </div>
                    <div class="form-group mt-2">
                        <label>End</label>
                        <input class="form-control" type="date" name="end">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ====== JavaScript Section ====== -->
<script>
$(document).ready(function() {

    // Show modals
    $('#addExam').click(() => $('#examModel').modal('show'));
    $('#addAcademicYear').click(() => $('#ayModal').modal('show'));

    // Load subjects dynamically when class is selected
    $('#classSelect').on('change', function() {
        let classId = $(this).val();
        if (!classId) return;

        $.ajax({
            url: "<?= base_url('admin/getSubjectsByClass') ?>?" + classId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                let html = '';
                if (response.length > 0) {
                    html += `<table class="table table-bordered">
                               <thead><tr><th>Subject</th><th>Has Practical</th><th>Is Subsidiary</th></tr></thead>
                               <tbody>`;
                    response.forEach(sub => {
                        html += `<tr>
                            <td>${sub.subject}</td>
                            <td><input type="checkbox" name="select[${sub.id}][has_practical]" value="1"></td>
                            <td><input type="checkbox" name="select[${sub.id}][is_subsidiary]" value="1"></td>
                        </tr>`;
                    });
                    html += `</tbody></table>`;
                } else {
                    html = `<p class="text-muted">No subjects found for this class.</p>`;
                }
                $('#subjectListOption').html(html);
            }
        });
    });

    // Submit Exam Form
    $('#examForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/saveExam') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                alert('Exam saved successfully!');
                $('#examModel').modal('hide');
                $('#examForm')[0].reset();
            },
            error: function() {
                alert('Error saving exam.');
            }
        });
    });

    // Submit Academic Year Form
    $('#academicYear').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('admin/saveAcademicYear') ?>",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                alert('Academic Year added successfully!');
                $('#ayModal').modal('hide');
                $('#academicYear')[0].reset();
            },
            error: function() {
                alert('Error adding academic year.');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>