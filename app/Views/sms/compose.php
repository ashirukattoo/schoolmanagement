<?= $this->extend('teacher/includes/default'); ?>
<?= $this->section('content'); ?>
    <!-- Dashboard Cards -->
    <div class="container-fluid p-4">
        <h3>Compose SMS</h3>
        <?php if(session()->getFlashdata('success')): ?>

            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <form action="<?= base_url('sms/send') ?>"
              method="post">

            <div class="mb-3">

                <label>Phone Number</label>

                <input type="text"
                       name="phone"
                       class="form-control"
                       required>

            </div>

            <div class="mb-3">

                <label>Message</label>

                <textarea name="message"
                          rows="5"
                          class="form-control"
                          required></textarea>

            </div>

            <button class="btn btn-primary">
                Queue SMS
            </button>
        </form>
    </div>
<?= $this->endSection(); ?>
