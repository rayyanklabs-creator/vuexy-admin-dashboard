// ================================
// Main DataTable configuration (common for both)
// ================================
var MainDataTableConfig = {
    retrieve: true,
    dom: '<"row"<"col-md-2"<l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0"fB>>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    language: {
        sLengthMenu: 'Show _MENU_',
        search: '',
        searchPlaceholder: 'Search...',
        processing: '<div class="datatable-loader"></div> Loading...',
        paginate: {
            next: '<i class="ti ti-chevron-right ti-sm"></i>',
            previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
    },
    buttons: [{
        extend: 'collection',
        className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light border-left-0 border-right-0 rounded',
        text: '<i class="ti ti-upload ti-xs me-sm-1 align-text-bottom"></i> <span class="d-none d-sm-inline-block">Export</span>',
        buttons: [
            { extend: 'print', text: '<i class="ti ti-printer me-1"></i>Print', className: 'dropdown-item', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'csv', text: '<i class="ti ti-file-text me-1"></i>Csv', className: 'dropdown-item', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'excel', text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel', className: 'dropdown-item', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'pdf', text: '<i class="ti ti-file-description me-1"></i>Pdf', className: 'dropdown-item', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'copy', text: '<i class="ti ti-copy me-1"></i>Copy', className: 'dropdown-item', exportOptions: { columns: ':not(:last-child)' } }
        ]
    }]
};

// Simple loading indicator approach
const addSimpleLoadingIndicator = (dtTable) => {
    var $wrapper = dtTable.closest('.card-datatable');

    var loadingHtml = '<div class="simple-datatable-loader" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">' +
        '<div class="spinner-border text-primary" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
        '</div>';

    $wrapper.append(loadingHtml);

    var $loader = $wrapper.find('.simple-datatable-loader');

    dtTable.on('processing.dt', function (e, settings, processing) {
        if (processing) {
            $loader.fadeIn(120);
        } else {
            $loader.fadeOut(120);
        }
    });

    $loader.fadeIn(120);
    dtTable.on('init.dt', function () {
        $loader.fadeOut(120);
    });
}


// ================================
// Server-side DataTable
// ================================
const initServerSideDataTable = (ajaxUrl, columns, pageLength = 10) => {
    var dtTable = $('.custom-datatables');
    if (dtTable.length && !$.fn.DataTable.isDataTable(dtTable)) {

        let dataTableInstance = dtTable.DataTable($.extend(true, {
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                type: "GET",
                error: function (xhr, status, error) {
                    console.log(error);
                }

            },
            columns: columns,
            pageLength: pageLength,
        }, MainDataTableConfig));

        addSimpleLoadingIndicator(dtTable);

        fixDataTableStyling();
        return dataTableInstance;
    }
    return null;
}

// ================================
// Client-side DataTable
// ================================
const initClientSideDataTable = (pageLength = 10) => {
    var dtTable = $('.custom-datatables');
    if (dtTable.length && !$.fn.DataTable.isDataTable(dtTable)) {
        let dataTableInstance = dtTable.DataTable($.extend(true, {
            pageLength: pageLength,
            ordering: true,
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: -1,
                    orderable: false,
                    searchable: false
                }
            ],
            createdRow: function (row, data, dataIndex) {
                $('td', row).each(function () {
                    $(this).html($(this).html());
                });
            }
        }, MainDataTableConfig));

        // addSimpleLoadingIndicator(dtTable);

        fixDataTableStyling();
        return dataTableInstance;
    }
    return null;
};

// ================================
// Styling fix for both
// ================================
const fixDataTableStyling = () => {
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
    $('.dataTables_filter').addClass('ms-n4 me-4 mt-0 mt-md-6');
}