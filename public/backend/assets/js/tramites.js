$(document).ready(function () {
    const municipiosRoute = $('#municipiosRoute').val();
    const baseRoute = $('#baseRoute').val();
    const storeRoute = $('#storeRoute').val();
    const csrfToken = $('input[name="_token"]').val();

    let formatsData = [];

    // Cambio de estado para cargar municipios
    $('#StateId').change(function () {
        const estadoId = $(this).val();
        const municipioSelect = $('#MunicipalityId');

        if (estadoId) {
            fetch(`${municipiosRoute}/${estadoId}`)
                .then(response => response.json())
                .then(municipios => {
                    municipioSelect.html('<option value="">Seleccione un municipio</option>');
                    municipios.forEach(municipio => {
                        municipioSelect.append(new Option(municipio.MunicipioNombre, municipio.MunicipioId));
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            municipioSelect.html('<option value="">Seleccione un municipio</option>');
        }
    });

    // Function to handle adding items to tables
    function addItemToTable(buttonId, inputIds, tableId) {
        $(`#${buttonId}`).click(function () {
            const inputs = inputIds.map(id => {
                const inputElement = $(`#${id}`);
                if (window.editorInstances && window.editorInstances[id]) {
                    return window.editorInstances[id].getData();
                } else {
                    return inputElement.val();
                }
            });
            const valid = inputs.every(input => input);

            if (valid) {
                const table = $(`#${tableId} tbody`);
                const newRow = $('<tr></tr>');

                // Remove "Sin datos" row if it exists
                if (table.find('tr').length === 1 && table.find('tr td').text() === 'Sin datos') {
                    table.find('tr').remove();
                }

                // Get the next correlativo number
                const nextNumber = table.find('tr').length + 1;
                newRow.append(`<td>${nextNumber}</td>`);

                inputs.forEach(input => {
                    newRow.append(`<td>${input}</td>`);
                });

                // Add action column with delete button
                newRow.append(`<td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fas fa-trash"></i></button></td>`);

                table.append(newRow);

                // Update correlativo numbers
                updateCorrelativo(tableId);

                // Limpiar inputs normales y CKEditor
                inputIds.forEach(id => {
                    const inputElement = $(`#${id}`);
                    inputElement.val('');
                    if (window.editorInstances && window.editorInstances[id]) {
                        window.editorInstances[id].setData('');
                    }
                });
            } else {
                toastr.info('Por favor, complete todos los campos antes de agregar el ítem.');
            }
        });
    }

    // Function to update correlativo numbers
    function updateCorrelativo(tableId) {
        const table = $(`#${tableId} tbody`);
        table.find('tr').each((index, row) => {
            $(row).find('td:first').text(index + 1);
        });
    }

    // Event delegation to handle dynamic deletion of rows
    $(document).on('click', '.delete-row', function () {
        const row = $(this).closest('tr');
        const tableId = row.closest('table').attr('id');
        row.remove();

        // Update correlativo numbers
        updateCorrelativo(tableId);

        // Add "Sin datos" row if table is empty
        const tableBody = $(`#${tableId} tbody`);
        if (tableBody.find('tr').length === 0) {
            const colCount = $(`#${tableId} thead tr th`).length;
            tableBody.append(`<tr><td colspan="${colCount}" class="text-center">Sin datos</td></tr>`);
        }
    });

    addItemToTable('addContact', ['ContactName', 'ContactEmail', 'ContactNumber'], 'contactsTable');
    addItemToTable('addLegalBase', ['LegalBaseText_hidden'], 'legalBasesTable');
    addItemToTable('addInformation', ['InformationText_hidden'], 'informationsTable');
    addItemToTable('addProcedure', ['ProcedureProcedureText_hidden'], 'proceduresTable');
    addItemToTable('addRequirement', ['RequirementName', 'RequirementOriginal', 'RequirementCopiesNumber', 'RequirementObservations'], 'requirementsTable');
    addItemToTable('addPhone', ['PhoneNumber', 'PhoneExtension'], 'phonesTable');

    // Handle adding formats with file preview, download button, and filename badge
    $('#addFormat').click(function () {
        const formatName = $('#FormatName').val();
        const formatNumber = $('#FormatNumber').val();
        const formatFile = $('#FormatFile')[0].files[0];

        if (formatName && formatFile && formatNumber) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const table = $('#formatsTable tbody');
                const newRow = $('<tr></tr>');

                // Remove "Sin datos" row if it exists
                if (table.find('tr').length === 1 && table.find('tr td').text() === 'Sin datos') {
                    table.find('tr').remove();
                }

                // Get the next correlativo number
                const nextNumber = table.find('tr').length + 1;
                newRow.append(`<td>${nextNumber}</td>`);

                newRow.append(`<td>${formatName}</td>`);
                newRow.append(`<td>${formatNumber}</td>`);
                newRow.append(`<td data-file-name="${formatFile.name}" data-file-index="${nextNumber - 1}"><a href="${e.target.result}" download="${formatFile.name}" class="btn btn-info btn-sm"><i class="fas fa-download"></i> Descargar archivo <span class="badge badge-secondary">${formatFile.name}</span></a></td>`);
                newRow.append(`<td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fas fa-trash"></i></button></td>`);

                table.append(newRow);

                // Save the file data
                formatsData.push({
                    FormatName: formatName,
                    FormatNumber: formatNumber,
                    FormatFile: formatFile
                });

                // Update correlativo numbers
                updateCorrelativo('formatsTable');

                $('#FormatName').val('');
                $('#FormatNumber').val('');

                // Limpiar el input file
                $('#FormatFile').val(null);
                $('#FormatFile').next('.custom-file-label').html('Choose file');
            };

            reader.readAsDataURL(formatFile);
        } else {
            toastr.info('Por favor, complete todos los campos antes de agregar el formato.');
        }
    });

    // Collect contacts from table
    function collectContacts() {
        const contacts = [];
        $('#contactsTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                contacts.push({
                    ContactName: cells.eq(1).text(),
                    ContactEmail: cells.eq(2).text(),
                    ContactNumber: cells.eq(3).text()
                });
            }
        });
        return contacts;
    }

    // Collect legal bases from table
    function collectLegalBases() {
        const legalBases = [];
        $('#legalBasesTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                legalBases.push({
                    LegalBaseText: cells.eq(1).html() // Get HTML content
                });
            }
        });
        return legalBases;
    }

    // Collect informations from table
    function collectInformations() {
        const informations = [];
        $('#informationsTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                informations.push({
                    InformationText: cells.eq(1).html() // Get HTML content
                });
            }
        });
        return informations;
    }

    // Collect procedures from table
    function collectProcedures() {
        const procedures = [];
        $('#proceduresTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                procedures.push({
                    ProcedureProcedureText: cells.eq(1).html() // Get HTML content
                });
            }
        });
        return procedures;
    }

    // Collect requirements from table
    // Collect requirements from table
    function collectRequirements() {
        const requirements = [];
        $('#requirementsTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                requirements.push({
                    RequirementName: cells.eq(1).text(),
                    RequirementOriginal: cells.eq(2).text().trim().toLowerCase() === 'sí' ? 1 : 0,
                    RequirementCopiesNumber: parseInt(cells.eq(3).text(), 10) || 0,
                    RequirementObservations: cells.eq(4).text() // Get HTML content
                });
            }
        });
        return requirements;
    }

    // Collect phones from table
    function collectPhones() {
        const phones = [];
        $('#phonesTable tbody tr').each(function () {
            const cells = $(this).find('td');
            if (cells.length > 1) {
                phones.push({
                    PhoneNumber: cells.eq(1).text(),
                    PhoneExtension: cells.eq(2).text()
                });
            }
        });
        return phones;
    }

    // Handle form submission with SweetAlert
    $('#tramiteForm').submit(async function (event) {
        event.preventDefault();

        // Mostrar SweetAlert de carga
        Swal.fire({
            title: 'Enviando...',
            text: 'Por favor, espera mientras se guarda el trámite.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);
        formData.append('_token', csrfToken);

        // Add contacts to formData
        const contacts = collectContacts();
        formData.append('contacts', JSON.stringify(contacts));

        // Add formats to formData
        formatsData.forEach((format, index) => {
            formData.append(`formats[${index}][FormatName]`, format.FormatName);
            formData.append(`formats[${index}][FormatNumber]`, format.FormatNumber);
            formData.append(`formats[${index}][FormatFile]`, format.FormatFile); // Append the actual file
        });

        // Add legal bases to formData
        const legalBases = collectLegalBases();
        formData.append('legalBases', JSON.stringify(legalBases));

        // Add informations to formData
        const informations = collectInformations();
        formData.append('informations', JSON.stringify(informations));

        // Add procedures to formData
        const procedures = collectProcedures();
        formData.append('procedures', JSON.stringify(procedures));

        // Add requirements to formData
        const requirements = collectRequirements();
        formData.append('requirements', JSON.stringify(requirements));

        // Add phones to formData
        const phones = collectPhones();
        formData.append('phones', JSON.stringify(phones));

        // Sync CKEditor data to form
        if (window.editorInstances) {
            Object.keys(window.editorInstances).forEach(key => {
                const editor = window.editorInstances[key];
                formData.append(key, editor.getData());
            });
        }

        try {
            const response = await fetch(storeRoute, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'El trámite ha sido guardado correctamente.',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location.href = baseRoute;
                    }
                });
            } else if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join('<br>');
                Swal.fire({
                    title: 'Error de validación',
                    html: errorMessages,
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire('Error', 'Hubo un problema al guardar el trámite.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Hubo un problema al guardar el trámite.', 'error');
        }
    });

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize CKEditor for textareas
    const editors = [
        '#ProcedureDescription',
        '#ProcedureUtility',
        '#ProcedureProcedureCriteria',
        '#LegalBaseText',
        '#InformationText',
        '#ProcedureProcedureText'
    ];

    editors.forEach(selector => {
        ClassicEditor
            .create(document.querySelector(selector))
            .then(editor => {
                // Añadir el editor a un objeto global de instancias
                if (!window.editorInstances) {
                    window.editorInstances = {};
                }
                window.editorInstances[selector.replace('#', '')] = editor;

                // Sincronizar el contenido del editor con el input oculto
                editor.model.document.on('change:data', () => {
                    $(`${selector}_hidden`).val(editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
});
