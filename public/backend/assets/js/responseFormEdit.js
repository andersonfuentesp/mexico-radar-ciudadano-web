$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    $('#StateId').change(function() {
        var estadoId = $(this).val();
        var municipioSelect = $('#MunicipalityId');
        var municipiosRoute = $('#municipiosRoute').val();

        if (estadoId) {
            fetch(`${municipiosRoute}/${estadoId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
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
});