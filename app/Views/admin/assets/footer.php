<!-- DataTables Core -->
<link rel="stylesheet" href="<?= base_url('assets/datatables/dataTables.bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/dataTables.bootstrap4.min.js') ?>"></script>

<!-- DataTables Buttons -->
<link rel="stylesheet" href="<?= base_url('assets/datatables/buttons.bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/datatables/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/pdfmake/build/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/datatables/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/datatables/buttons.print.min.js') ?>"></script>

<!-- Chart.js (if needed for charts) -->
<script src="<?= base_url('assets/chartjs/Chart.min.js') ?>"></script>

<!-- Custom scripts -->
<script src="<?= base_url('assets/js/datatable-exports.js') ?>"></script>
<script src="<?= base_url('adminLite/js/adminlte.js') ?>"></script>

<script>
    $(document).ready(function() {
        // Sidebar toggle
        $('.toggle-btn').click(function() {
            $('#sidebar').toggleClass('collapsed');
        });
        //Sub menutotgle
        $('.submenu-toggle').click(function(e){
            e.preventDefault();
            var submenu  = $(this).next('.submenu');
            submenu.toggleClass('show');
            (this).next('i.fas.fa-chevron-down').toggleClass('rotate')
        });

        // Optional: Initialize all DataTables on the page
        $('.datatable').DataTable();
    });
</script>
<script src="<?= base_url('assets/js/custom/addGuardian.js') ?>"></script>
<script src="<?= base_url('assets/js/custom/addAcademicYear.js') ?>"></script>
<script src="<?= base_url('assets/js/custom/formDynamism.js') ?>"></script>
</body>
</html>
