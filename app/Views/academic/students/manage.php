<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>

<div class="container mt-0">
    <div class="card">
        <div class="card-header bg-info text-white">   
            <h6>Manage Students Records</h6>
            <a href="<?= site_url('admin/students/promote/') ?>"
               class="btn btn-warning btn-sm"
               onclick="return confirm('Promote all students For New Academic Year?')">
               O-level Promote
            </a>
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
            <?php endif; ?>

            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr class="table-info">
                        <th>#</th>
                        <th>Name</th>
                        <th>Class-Stream</th>
                        <th>Guardian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($students)):
                    $count = 1;
                    foreach ($students as $s): 
                        $isActive = strtolower($s['stuStatus']) == 'active';
                        ?>
                        <tr class="<?= $isActive ? 'table-success' : 'table-warning' ?>" data-student-id="<?= $s['stuID'] ?>">
                            <td><?= $count++; ?></td>
                            <td><?= esc($s['stuFname'].' '.$s['stuMname'].' '.$s['stuSurname']) ?></td>
                            <td><?= esc($s['class'].'-'.$s['stream']) ?></td>
                            <td>
                                <?php if(empty($s['guardian'])): ?>
                                    <button class="btn btn-sm btn-outline-info addGuardianBtn" 
                                        data-student-id="<?= $s['stuID'] ?>" 
                                        data-student-name="<?= esc($s['stuFname'].' '.$s['stuMname'].' '.$s['stuSurname']) ?>">
                                        Add Guardian
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= esc($s['guardian']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($isActive): ?>
                                    <a href="<?= base_url('admin/del/student/'.$s['stuID']) ?>" class="btn btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
<a href="<?= site_url('admin/streams/promote/'.$s['stream_id']) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Promote all students in this stream?')">
   Promote
</a>


                                    <a href="<?= base_url('admin/manage/student') ?>?transfer=<?= $s['stuID'] ?>" class="btn btn-info" title="Transfer">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <a href="<?= base_url('admin/manage/student') ?>?edit=<?= $s['stuID'] ?>" class="btn btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/manage/student') ?>?unblock=<?= $s['stuID'] ?>" class="btn btn-success" title="Unblock">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No students found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Guardian Modal -->
<div class="modal fade" id="guardianModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Assign Guardian to <span id="studentName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="guardianForm">
          <?= csrf_field() ?>
          <input type="hidden" name="student_id" id="student_id">
          <input type="hidden" name="use_existing" id="use_existing" value="1">

          <!-- Existing guardian dropdown -->
          <div id="existingGuardianSection">
            <label for="existing_guardian_id" class="form-label">Select Existing Guardian</label>
            <select name="existing_guardian_id" id="existing_guardian_id" class="form-select" ></select>

            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="toggleNewGuardian">
              <label class="form-check-label" for="toggleNewGuardian">Add New Guardian Instead</label>
            </div>
          </div>

          <!-- New guardian fields -->
          <div id="newGuardianSection" class="mt-3 d-none">
            <div class="mb-2">
              <label class="form-label">Guardian Name</label>
              <input type="text" name="guardian_name" id="guardian_name" class="form-control">
            </div>
            <div class="mb-2">
              <label class="form-label">Sex</label>
              <select name="sex" id="sex" class="form-control">
                <option value="female">Female</option>
                <option value="male">Male</option>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Occupation</label>
              <input type="text" name="occupation" id="occupation" class="form-control">
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control">
            </div>
          </div>

          <div class="text-end mt-3">
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

    // Open modal
    $('.addGuardianBtn').on('click', function(){
        let studentId = $(this).data('student-id');
        let studentName = $(this).data('student-name');

        $('#student_id').val(studentId);
        $('#studentName').text(studentName);
        $('#toggleNewGuardian').prop('checked', false);
        $('#newGuardianSection').addClass('d-none');
        $('#use_existing').val('1');

        loadGuardians();
        $('#guardianModal').modal('show');
    });

    // Load existing guardians
    function loadGuardians() {
        $.ajax({
            url: '<?= base_url("admin/guardian_list/student") ?>',
            method: 'GET',
            dataType: 'json',
            success: function(guardians){
                let options = '<option value="">-- Select Guardian --</option>';
                guardians.forEach(g => {
                    options += `<option value="${g.guID}">${g.empname}</option>`;
                });
                $('#existing_guardian_id').html(options);
            },
            error: function(xhr){
                alert('Failed to load guardians: ' + xhr.status + ' ' + xhr.statusText);
            }
        });
    }

    // Toggle between existing/new guardian
    $('#toggleNewGuardian').on('change', function(){
        if($(this).is(':checked')){
            $('#newGuardianSection').removeClass('d-none');
            $('#use_existing').val('0');
            $('#existing_guardian_id').prop('required', false);
            $('#existing_guardian_id').val(''); // clear value
        } else {
            $('#newGuardianSection').addClass('d-none');
            $('#use_existing').val('1');
            $('#existing_guardian_id').prop('required', true);
        }
    });


    // Save guardian assignment
    $('#guardianForm').on('submit', function(e){
    e.preventDefault();

    let useExisting = $('#use_existing').val();
    let studentId = $('#student_id').val();
    let existingId = $('#existing_guardian_id').val();

    // If using existing, make sure a valid guardian is selected
    if(useExisting == 1 && !existingId){
        alert('Please select a guardian');
        return;
    }

    $.ajax({
        url: '<?= site_url("admin/guardian/student") ?>',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response){
            $('#guardianModal').modal('hide');
            if(response.status === 'success'){
                alert(response.message);
                let guardianName = response.guardian.empname;
                let row = $('tr[data-student-id="' + studentId + '"] td:nth-child(4)');
                row.html('<span class="badge bg-success">' + guardianName + '</span>');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr){
            $('#guardianModal').modal('hide');
            alert('Error connecting to server: ' + xhr.status + ' ' + xhr.responseText);
        }
    });
});


});
</script>

<?= $this->endSection() ?>
