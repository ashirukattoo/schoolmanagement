<?= $this->extend('admin/assets/default'); ?>
<?= $this->section('content'); ?>
<div class="container mt-1">
    <div class="card  card-outline-info">
        <div class="card-header bg-info align-content-center text-light p-2 ">   
            <h6 style="align-content: justify;"><?= $pageTitle ?> <button style="float: right;" id="addExam" class="btn btn-sm btn-outline-light "><i class="fas fa-file-excel"></i> Add Exam</button></h6>            
        </div>
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
            <table id="dataTable" class="table datatable table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Exam</th>
                        <th>Class</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($exams)):
                    $count=1;  ?>
                    <?php foreach ($exams as $s): ?>
                        <tr>
                            <td><?= $count; ?></td>
                            <td><?= strtoupper($s['exam']) ?></td>
                            <td><?= $s['class'] ?></td>
                            <td><?= $s['year'] ?? 'NIL' ?></td>
                            <td><?= $s['status'] ?></td>
                            <td class="text-nowrap">
                                <!-- ===== Desktop buttons ===== -->
                                <div class="d-none d-md-inline-block">
                                    <?php if($s['status'] === 'progress'): ?>
                                        <a href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-primary"
                                           onclick="return confirm('Are you sure you want to compile results for this exam and class?')">
                                           <i class="fas fa-cogs"></i> Compile
                                        </a>
                                        <a href="<?= base_url('admin/download_score_template/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-info">
                                           <i class="fas fa-file-excel"></i> Template
                                        </a>
                                    <?php elseif($s['status'] === 'current'): ?>
                                        <a title="Recompile" href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           onclick="return confirm('Are you sure you want to recompile results for this exam?')">
                                           <i class="fas fa-cogs"></i>
                                        </a>
                                        <a href="<?= base_url('admin/results/analytics/'.$s['id']) ?>"
                                           class="btn btn-sm btn-outline-info">
                                           <i class="fas fa-analytics"></i> Analyse
                                        </a>
                                        <a title=" View Results" href="<?= base_url('admin/results/class/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-outline-primary">
                                           <i class="fas fa-eye"></i> 
                                        </a>
                                        <a href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('Really you want to forward results?')">
                                           <i class="fas fa-forward"></i> Forward
                                        </a>
                                        <a href="<?= base_url('admin/report_cards_class/'.$s['id'].'/'.$s['cid']) ?>"
                                           class="btn btn-sm btn-primary">
                                           PDF Cards
                                        </a>
                                    <?php elseif($s['status'] === 'next'): ?>
                                        <a href="<?= base_url('/admin/edit/exams/'.$s['id']) ?>"
                                           class="btn btn-sm btn-outline-primary">
                                           <i class="fas fa-eye"></i> Current
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <!-- ===== Mobile 3-dots dropdown ===== -->
                                <div class="dropdown d-md-none">
                                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if($s['status'] === 'progress'): ?>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>"
                                                   onclick="return confirm('Compile results?')">
                                                    <i class="fas fa-cogs me-2"></i> Compile
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/download_score_template/'.$s['id'].'/'.$s['cid']) ?>">
                                                    <i class="fas fa-file-excel me-2"></i> Template
                                                </a>
                                            </li>

                                        <?php elseif($s['status'] === 'current'): ?>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>">
                                                    <i class="fas fa-cogs me-2"></i> Recompile
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/results/class/'.$s['id'].'/'.$s['cid']) ?>">
                                                    <i class="fas fa-eye me-2"></i> Results
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/compile/results/'.$s['id'].'/'.$s['cid']) ?>"
                                                   onclick="return confirm('Forward results?')">
                                                    <i class="fas fa-forward me-2"></i> Forward
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('admin/report_cards_class/'.$s['id'].'/'.$s['cid']) ?>">
                                                    <i class="fas fa-file-pdf me-2"></i> Generate PDF
                                                </a>
                                            </li>

                                        <?php elseif($s['status'] === 'next'): ?>
                                            <li>
                                                <a class="dropdown-item"
                                                   href="<?= base_url('/admin/edit/exams/'.$s['id']) ?>">
                                                    <i class="fas fa-eye me-2"></i> Current
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $count++;
                        endforeach; 
                    ?>
                <?php else: ?>
                    <tr><td colspan="6"><center>No Record found.</center></td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="examModel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Add New Exam</h5>
                <button class="btn-close" id="btndismiss" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="examfForm" action="/admin/save/exams" method="post" >
                    <div class="row">
                        <div class="form-group">
                            <label>Examination Name</label>
                            <input class="form-control" type="text" name="name" placeholder="E.g: Annual Examination" required>
                        </div>
                        <div class="form-group mt-2">
                            <label>Academic Year</label>
                            <select class="form-control yearRow" name="year" required >
                                <option>--Select Academic Year--</option>
                                <?php foreach ($years as $key => $row) { ?>
                                   <option value="<?= $row['ayID'] ?>"><?= $row['ayName'] ?></option>
                                <?php }  ?>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Terms</label>
                            <select class="form-control termRow" name="terms" required >
                                <option>--Select Term --</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Class</label>
                            <select class="form-control" name="class" required >
                                <option>--Select Class--</option>
                                <?php foreach ($classes as $key => $row) { ?>
                                   <option value="<?= $row['cid'] ?>"><?= $row['named'] ?></option>
                                <?php }  ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="text-end">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script> 
    const BASE_URL = "<?= base_url() ?>"; 
    $(document).ready(function () {
        $('.yearRow').on('change', function () {
            let ayID = $(this).val();
            let termSelect = $('.termRow');

            termSelect.html('<option>Loading terms...</option>');

            if (ayID === '' || ayID === '--Select Academic Year--') {
                termSelect.html('<option>--Select Term--</option>');
                return;
            }

            $.ajax({
                url: BASE_URL + '/admin/get-terms-by-year',
                type: 'POST',
                data: {
                    ayID: ayID,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function (response) {
                    termSelect.empty();
                    termSelect.append('<option value="">--Select Term--</option>');

                    if (response.length > 0) {
                        $.each(response, function (i, term) {
                            termSelect.append(
                                '<option value="' + term.tID + '">' + term.tName + '</option>'
                            );
                        });
                    } else {
                        termSelect.append('<option value="">No terms found</option>');
                    }
                },
                error: function () {
                    termSelect.html('<option>Error loading terms</option>');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>