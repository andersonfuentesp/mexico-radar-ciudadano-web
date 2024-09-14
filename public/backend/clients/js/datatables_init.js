function initializeDataTables(config) {
    var hasDataRows = $('#myTable tbody tr').not('.no-data').length > 0; // Verifica si hay filas sin la clase .no-data

    var columnsToApplyRender = config.columnsToApplyRender;
    var orderConfig = [[0, 'asc']]; // Configuración de orden predeterminada

    if (config.orderColumn) {
        var orderColumn = Object.keys(config.orderColumn)[0];
        var orderType = config.orderColumn[orderColumn];
        orderConfig = [[orderColumn, orderType]];
    }

    // Preparar las definiciones de columnas base
    var columnDefs = [{
        className: 'py-4 px-6 border-r whitespace-nowrap',
        targets: '_all'
    }];

    // Condición ajustada para agregar configuraciones de renderizado
    if (hasDataRows) {
        // Extender las definiciones de columnas con configuraciones de renderizado específicas
        columnDefs.push(...Object.keys(columnsToApplyRender).map(columnIndex => ({
            targets: Number(columnIndex),
            render: function(data, type, row, meta) {
                return `<div class="text-${columnsToApplyRender[columnIndex]} align-middle">${data}</div>`;
            }
        })));
    }

    // Inicializar DataTables con las configuraciones preparadas
    $('#myTable').DataTable({
        createdRow: function (row, data, dataIndex) {
            $(row).addClass('hover:bg-gray-100 border-b');
        },
        columnDefs: columnDefs,
        responsive: true,
        autoWidth: false,
        order: orderConfig,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'custom-button bg-blue-500 hover:bg-blue-600 text-white rounded m-2',
                text: 'Exportar a Excel'
            },
            {
                extend: 'pdf',
                className: 'custom-button bg-blue-500 hover:bg-blue-600 text-white rounded m-2',
                text: 'Exportar a PDF'
            },
            {
                extend: 'csv',
                className: 'custom-button bg-blue-500 hover:bg-blue-600 text-white rounded m-2',
                text: 'Exportar a CSV'
            }
        ],
        initComplete: function () {
            addCustomButton(config.buttonText, config.buttonAction);
        },
    });
}

function addCustomButton(text, action) {
    var buttonsContainer = $('.dt-buttons');
    if (text !== "" && buttonsContainer.find('custom-button').length === 0) { // Verifica si el botón personalizado ya existe
        var customButton = $('<button>')
            // Utiliza la misma clase para el botón personalizado que para los botones de DataTables
            .addClass('custom-button bg-blue-500 hover:bg-blue-600 text-white rounded m-2')
            .text(text)
            .on('click', action);

        buttonsContainer.append(customButton);
    }
}