function initDataTableExport(tableId, fileName = 'export') {
    if (!$.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    title: fileName,
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    title: fileName,
                    orientation: 'portrait',
                    pageSize: 'A4',
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10;
                    },
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    title: fileName,
                    exportOptions: { columns: ':visible' }
                }
            ]
        });
    }
}
