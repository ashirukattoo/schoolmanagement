<?= $this->extend('templates/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5>Employee Registration</h5>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <!-- Example Field -->
                    <div class="mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter First name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mname" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="mname" name="mname" placeholder="Enter Second name" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Last name" required>
                    </div>

                    <!-- Example Select -->
                    <div class="mb-3">
                        <label for="sex" class="form-label">Sex</label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option value="">Select Sex</option>
                            <option value="1">Female</option>
                            <option value="2">Male</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dob" class="form-label">Birth Date</label>
                        <input type="Date" class="form-control" id="dob" name="dob" placeholder="Birth Date" required>
                    </div>

                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class" required>
                            <option value="1">Form I</option>
                            <option value="2">Form II</option>
                            <option value="3">Form III</option>
                            <option value="4">Form IV</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>