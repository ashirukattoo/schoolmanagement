<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
    <!-- Dashboard Cards -->
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header bg-info text-dark pb-0">
                <div class="d-flex justify-content-between">
                    <h4><?= esc($pageTitle) ?></h4>
                    <a href="<?= base_url('sms/queue') ?>"
                       class="btn btn-success">
                        Pending SMS
                    </a>
                </div>
            </div>
            <div class="card-body card-outline-info">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <form id="resultSMSForm">
                            <div class="mb-3">
                                <label>Select Class</label>
                                <select class="form-control"
                                        name="class_id"
                                        id="class_id">
                                    <option value="">Select Class</option>
                                    <?php foreach($classes as $class): ?>
                                        <option value="<?= $class['cid'] ?>">
                                            <?= $class['named'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3 d-none" id="examWrapper">
                                <label>Select Exam</label>
                                <select class="form-control"
                                        name="exam_id"
                                        id="exam_id">
                                    <option value="">Select Exam</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-info d-none"
                                    id="loadRecipients"> Load Recipients
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <h6>List of Students (Recepients)</h6>
                        <table class="table table-stripped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>SNo.</th><th>Student</th><th>Contact</th><th>Content</th>
                                </tr>
                            </thead>
                            <tbody id="recipientTable"></tbody>
                        </table>
                        <button class="btn btn-success mt-3" id="sendBulkSMS">
                            Queue Result SMS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let smsMessages = [];
        /*
        |--------------------------------------------------------------------------
        | LOAD EXAMS WHEN CLASS CHANGES
        |--------------------------------------------------------------------------
        */
        $('#class_id').on('change', function(){
            let classID = $(this).val();
            $('#loadRecipients').addClass('d-none');      
            $('#exam_id').html(
                '<option value="">Select Exam</option>'
            );
            if(classID == ''){
                $('#examWrapper').addClass('d-none');
                return;
            }

            /*----------------------------------------------------------------
             FETCH EXAMS
            |-------------------------------------------------------- */
            $.ajax({
                url: "<?= base_url('sms/fetch-class-exams') ?>",
                type: "POST",
                data: {
                    class_id: classID
                },
                dataType: "json",
                success: function(response)
                {
                    let options =
                        '<option value="">Select Exam</option>';
                    response.forEach(function(exam){
                        options += `
                            <option value="${exam.exID}">
                                ${exam.exName}
                            </option>
                        `;
                    });
                    $('#exam_id').html(options);
                    /*-----------------------------------
                      SHOW EXAM DROPDOWN
                    |------------------------------------------------ */
                    $('#examWrapper').removeClass('d-none');
                }
            });
        });
        //  SHOW BUTTON AFTER EXAM SELECTED
        $('#exam_id').on('change', function(){
            let examID = $(this).val();
            if(examID == ''){
                $('#loadRecipients').addClass('d-none');
            }
            else {
                $('#loadRecipients').removeClass('d-none');
            }
        });
        $('#loadRecipients').on('click', function(){
            $.ajax({
                url: "<?= base_url('sms/fetch-exam-recipients') ?>",
                type: "POST",
                data: {
                    class_id: $('#class_id').val(),
                    exam_id: $('#exam_id').val()
                },
                dataType: "json",
                success: function(response)
                {
                    let html = '';
                    smsMessages = response;
                    response.forEach(function(row){
                        html += `
                            <tr>
                                <td>${row.sn}</td>
                                <td>${row.student}</td>
                                <td>${row.phone}</td>
                                <td>${row.message}</td>
                            </tr>`;
                    });
                    $('#recipientTable').html(html);
                }
            });
        });
        $('#sendBulkSMS').on('click', function(){
            $.ajax({
                url: "<?= base_url('sms/queue-exam-results') ?>",
                type: "POST",
                data: JSON.stringify({
                    messages: smsMessages
                }),
                contentType: "application/json",
                dataType: "json",
                success: function(response)
                {
                    alert(response.message);
                }

            });
        });
    </script>
<?= $this->endSection(); ?>
