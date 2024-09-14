$(document).ready(function() {
    $('#image').change(function(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#showImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    $('#state_id').change(function() {
        var estadoId = $(this).val();
        var municipioSelect = $('#municipality_id');
        var municipiosRoute = $('#municipiosRoute').val();

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

    // Deshabilitar selectores si el usuario tiene el rol "Responsable Radar" o "Responsable Reporte"
    if ($('#isResponsableRadar').val() === '1' || $('#isResponsableReporte').val() === '1') {
        $('#state_id, #municipality_id, #dependency_id, #report_type_id').prop('disabled', true);
    }

    // Validar el formulario
    $('#quickForm').validate({
        rules: {
            name: {
                required: true,
            },
            lastname: {
                required: true,
            },
            username: {
                required: true,
            },
            email: {
                required: true,
            },
            password: {
                required: false,
            },
            status: {
                required: true,
            },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
