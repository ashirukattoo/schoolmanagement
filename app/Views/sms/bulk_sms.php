<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
    <div class="container-fluid p-1">
        <div class="card">
            <div class="card-header bg-info text-dark p-1">
                <div class="d-flex justify-content-between">
                    <h4><?= esc($pageTitle) ?></h4>
                    <a href="<?= base_url('sms/queue') ?>"
                       class="btn btn-success">
                        Pending SMS
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>Select Class</label>
                            <select class="form-control"
                                    id="class_id">
                                <option value="">Select Class</option>
                                <?php foreach($classes as $class): ?>
                                    <option value="<?= $class['cid'] ?>">
                                        <?= $class['named'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Message</label>
                            <textarea class="form-control"
                                      rows="6"
                                      id="message"></textarea>
                        </div>
                        <button class="btn btn-success"
                                id="queueSMS">
                            Queue Bulk SMS
                        </button>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox"
                                               id="checkAll">
                                    </th>
                                    <th>Student</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody id="recipientTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let recipients = [];
        //LOAD RECIPIENTS
        $('#class_id').on('change', function(){
            $.ajax({
                url: "<?= base_url('sms/fetch-bulk-recipients') ?>",
                type: "POST",
                data: {
                    class_id: $(this).val()
                },
                dataType: "json",
                success: function(response){
                    recipients = response;
                    let html = '';
                    response.forEach(function(row, index){
                        html += `
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           class="recipient"
                                           value="${index}">
                                </td>
                                <td>${row.name}</td>
                                <td>${row.phone}</td>
                            </tr>
                        `;
                    });
                    $('#recipientTable').html(html);
                }
            });
        });
        //CHECK ALL
        $(document).on('change', '#checkAll', function(){
            $('.recipient').prop(
                'checked',
                $(this).is(':checked')
            );
        });
        // QUEUE BULK SMS
        $('#queueSMS').on('click', function(){
            let selected = [];
            $('.recipient:checked').each(function(){
                selected.push(
                    recipients[$(this).val()]
                );
            });

            $.ajax({
                url: "<?= base_url('sms/queue-bulk-sms') ?>",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    recipients: selected,
                    message: $('#message').val()
                }),
                dataType: "json",
                success: function(response){
                    alert(response.message);
                }
            });
        });
    </script>
<?= $this->endSection(); ?>