<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid p-1">
    <div class="card">
        <div class="card-header bg-info text-dark p-1">
            <div class="d-flex justify-content-between">
                <h4><?= esc($pageTitle) ?></h4>
                <a href="<?= base_url('sms/process') ?>"
                   class="btn btn-success">
                    Send Pending SMS
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="sms_platform" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Retries</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($messages)): ?>
                        <?php foreach($messages as $i => $sms): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($sms['phone']) ?></td>
                                <td><?= esc($sms['message']) ?></td>
                                <td>
                                    <?php if($sms['status'] == 'Pending'): ?>
                                        <span class="badge bg-warning">
                                            Pending
                                        </span>
                                    <?php elseif($sms['status'] == 'Sent'): ?>
                                        <span class="badge bg-success">
                                            Sent
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            Failed
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($sms['retries']) ?></td>
                                <td><?= esc($sms['created_at']) ?></td>
                                <td>
                                    <?php if($sms['status'] == 'Pending'): ?>
                                        <a href="<?= base_url('sms/send-single/'.$sms['id']) ?>"
                                           class="btn btn-sm btn-success">
                                            Send
                                        </a>
                                    <?php elseif($sms['status'] == 'Failed'): ?>
                                        <a href="<?= base_url('sms/retry/'.$sms['id']) ?>"
                                           class="btn btn-sm btn-warning">
                                            Retry
                                        </a>
                                    <?php else: ?>
                                        <span class="text-success">
                                            Sent
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                No queued SMS found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>