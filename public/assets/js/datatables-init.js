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
    }],
    initComplete: function () {
        if ($('#delete-selected').length === 0) {
            let deleteBtn = `
                <button id="delete-selected" class="btn delete_confirmation btn-danger ms-2 d-none">
                    <i class="ti ti-trash"></i>
                </button>
            `;
            $('.dt-buttons').append(deleteBtn);
        }
    }
};
// ======================================
// Simple loading indicator approach
// ======================================

const addSimpleLoadingIndicator = (dtTable) => {
    var wrapper = dtTable.closest('.custom-datatables');

    var loadingHtml = '<div class="simple-datatable-loader overflow-x-auto" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 1; display: flex; align-items: center; justify-content: center;">' +
        '<div class="spinner-border text-primary" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
        '</div>';

    wrapper.append(loadingHtml);

    var loader = wrapper.find('.simple-datatable-loader');

    dtTable.on('processing.dt', function (e, settings, processing) {
        if (processing) {
            loader.fadeIn(120);
        } else {
            loader.fadeOut(120);
        }
    });

    loader.fadeIn(120);
    dtTable.on('init.dt', function () {
        loader.fadeOut(120);
    });
}

// ================================
// Fix Responsive Wrapper
// ================================

const fixResponsiveWrapper = (dtTable) => {
    const table = $(dtTable);
    const card = table.closest('.card-datatable');
    const checkOverflow = () => {
        const isOverflowing = table[0].scrollWidth > table.outerWidth();
        if (isOverflowing) {
            card.addClass('table-responsive');
        } else {
            card.removeClass('table-responsive');
        }
    };

    table.on('draw.dt', function () {
        checkOverflow();
    });
    table.on('init.dt', function () {
        checkOverflow();
        let api = table.DataTable();
        let rowCount = api.data().length;
        if (rowCount === 0) {
            card.addClass('table-responsive');
            table.removeClass('table-responsive');
        } else {
            card.removeClass('table-responsive');
            table.addClass('table-responsive');
        }
    });
};

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
        fixResponsiveWrapper(dtTable);
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

// ================================
// Checkbox Selection Module
// ================================
const initDataTableCheckboxes = (dataTable, deleteButtonSelector = '#delete-selected') => {
    let selectedIds = [];

    // Select all checkbox change
    $(document).on('change', '#select-all', function() {
        const isChecked = $(this).is(':checked');
        const pageCheckboxes = dataTable.rows({ page: 'current' }).nodes().to$().find('.row-checkbox');

        pageCheckboxes.each(function() {
            const userId = $(this).val();

            if (isChecked) {
                $(this).prop('checked', true);
                if (!selectedIds.includes(userId)) selectedIds.push(userId);
            } else {
                $(this).prop('checked', false);
                selectedIds = selectedIds.filter(id => id !== userId);
            }
        });

        toggleDeleteButton();
        syncSelectAllCheckbox();
    });

    // Individual row checkbox change
    $(document).on('change', '.row-checkbox', function() {
        const userId = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedIds.includes(userId)) selectedIds.push(userId);
        } else {
            selectedIds = selectedIds.filter(id => id !== userId);
        }

        toggleDeleteButton();
        syncSelectAllCheckbox();
    });

    // Redraw event: maintain checkbox state
    dataTable.on('draw', function() {
        dataTable.rows({ page: 'current' }).nodes().to$().find('.row-checkbox').each(function() {
            const userId = $(this).val();
            $(this).prop('checked', selectedIds.includes(userId));
        });
        toggleDeleteButton();
        syncSelectAllCheckbox();
    });

    // Sorting or filtering clears selection
    dataTable.on('order.dt search.dt', function() {
        clearAllSelections();
    });

    // Helper functions
    const toggleDeleteButton = () => {
        if (selectedIds.length > 0) {
            $(deleteButtonSelector).removeClass('d-none').fadeIn(150);
        } else {
            $(deleteButtonSelector).fadeOut(150, function() {
                $(this).addClass('d-none');
            });
        }
    };

    const syncSelectAllCheckbox = () => {
        const allCheckboxes = dataTable.rows({ page: 'current' }).nodes().to$().find('.row-checkbox');
        const checkedCheckboxes = allCheckboxes.filter(':checked');
        $('#select-all').prop('checked', allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length);
    };

    const clearAllSelections = () => {
        selectedIds = [];
        $('#select-all').prop('checked', false);
        dataTable.rows().nodes().to$().find('.row-checkbox').prop('checked', false);
        toggleDeleteButton();
    };

    return {
        getSelectedIds: () => selectedIds,
        clearSelections: clearAllSelections
    };
};
