<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto p0 shadow-sm">
            <div class="card-header bg-info text-white"><h5 class="mb-0"><?= $pageTitle ?></h5></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">A: PERSONAL DETAILS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Name:</strong> <?= $emp['empFname'].' '.$emp['empMname'].' '.$emp['empSurname']; ?></td>
                            <td><strong>DOB:</strong> <?= $emp['empDob']; ?></td>
                            <td><strong>Sex:</strong> <?= $emp['empSex']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Email: </strong><?= $emp['empEmail']; ?></td>
                            <td><strong>Hired: </strong><?= $emp['empHired']; ?></td>
                            <td><strong>Status: </strong><?= $emp['empStatus']; ?></td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">B: EDUCATION DETAILS</th>
                        </tr>
                        <tr>
                            <td><strong>Primary:</strong> Kazinga P/S</td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Secondary: </strong>Rutunga S/S</td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>High School:</strong> taqwa S/S</td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>College: </strong><?= $emp['empEmail']; ?></td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>College:</strong> <?= $emp['empFname'].' '.$emp['empMname'].' '.$emp['empSurname']; ?></td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>University: </strong><?= $emp['empEmail']; ?></td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>University:</strong> <?= $emp['empFname'].' '.$emp['empMname'].' '.$emp['empSurname']; ?></td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>University: </strong><?= $emp['empEmail']; ?></td>
                            <td><strong>District:</strong> Bukoba DC</td>
                            <td><strong>Year:</strong> 2011</td>
                            <td>
                                <strong>Award:</strong> Certificate 
                                <a class="btn btn-sm btn-info" href="#"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-download"></i></a>
                                <a class="btn btn-sm btn-primary" href="#"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-center">C: CONTRACT DETAILS</th>
                        </tr>
                    </tbody>
                </table>          
            </div>
        </div>
    </div>
<?= $this->endSection() ?>