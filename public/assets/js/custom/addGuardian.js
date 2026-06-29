$(document).ready(function() {
    //Open Model
    $('.addGuardianBtn').on('click', function(){
        let studentId = $(this).data('student-id');
        let studentName = $(this).data('student-name');
        $('#student-id').val(studentId);
        $('#studentName').text(studentName);
        $('#toggleNewGuardian').prop('checked', false);
        $('newGuardianSection').addClass('d-none');
        $('#use_existing').val('1');
        loadGuardians();
        $('#guardianModal').modal('show');
    });
    //Load existing guardians into dropdown
    function loadGuardians() {
        $.ajax({
            url: '<?= site_url("admin/guardian_list/student") ?>',
            method: 'GET',
            dataType: 'json',
            success: function(guardians){
                let options = '<option value=""> -- Select Guardian --</option>';
                guardians.forEach(g=>{
                    options += '<option value="';
                    options += g.id;
                    options += '">';
                    options += g.empname;
                    options += '</option>';
                });
                $('#existing_guardian_id').html(options);
            }
        });
    }
    //Toggle between existing and new guardian
    $('#toggleNewGuardian').on('change', function(){
        if($(this).is(':checked')){
            $('#newGuardianSection').removeClass('d-none');
            $('#use_existing').val('0');
            $('#existing_guardian_id').prop('required', true);
        }else{
            $('newGuardianSection').addClass('d-none');
            $('#use_existing').val('1');
            $('#existing_guardian_id').prop('required', true);
        }
    });
    // Save guardian assignment
    $('#guardianForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?= site_url("admin/guardian/student") ?>',
            type: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $('#guardianModal').modal('hide');
                    alert(response.message);
                    let studentId = $('#student_id').val();
                    let guardianName = response.guardian.empname;
                    let row = $('tr[data-student-id="' + studentId + '"] td:last');
                    row.html('<span class="badge bg-success">' + guardianName + '</span>');
                } else {
                    alert(response.message);
                }
            }, 
            error: function(){
                alert('Error connecting to server');
            }
        });
    });
});