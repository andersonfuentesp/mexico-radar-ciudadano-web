$(document).ready(function () {
    const municipiosRoute = $('#municipiosRoute').val();

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

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize CKEditor for specific fields
    ClassicEditor
        .create(document.querySelector('#ProcedureDescription'))
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#ProcedureUtility'))
        .catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#ProcedureProcedureCriteria'))
        .catch(error => {
            console.error(error);
        });
});
