// public/js/userAdd.js

function random_password() {
    var pass = '';
    var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
        'abcdefghijklmnopqrstuvwxyz0123456789@#$';

    for (let i = 1; i <= 8; i++) {
        var char = Math.floor(Math.random() * str.length + 1);
        pass += str.charAt(char);
    }
    return pass;
}

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

    $('#dependency_id').change(function() {
        var dependencyIds = $(this).val();
        var reportTypeSelect = $('#report_type_id');
        var reportTypesRoute = $('#reportTypesRoute').val();

        if (dependencyIds.length > 0) {
            fetch(reportTypesRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ dependency_id: dependencyIds.join(',') })
            })
            .then(response => response.json())
            .then(data => {
                reportTypeSelect.empty();
                data.forEach(function(item) {
                    reportTypeSelect.append(new Option(item.label, item.value));
                });
            })
            .catch(error => console.error('Error:', error));
        }
    });

    $("#generate").click(function() {
        var pass = random_password();
        var x = Math.floor((Math.random() * 100) + 1);
        $("#password").val(pass + "#" + x + "Tf");
    });

    $("#show-password").click(function() {
        if ('password' == $('#password').attr('type')) {
            $('#password').prop('type', 'text');
        } else {
            $('#password').prop('type', 'password');
        }
    });

    // Función para manejar la habilitación y deshabilitación de selectores
    function handleRoleSelectors() {
        var selectedRole = $('#rol_id').find("option:selected").text();
        var isResponsableRadar = $('#isResponsableRadar').val() === '1';
        
        if (isResponsableRadar) {
            if (selectedRole === "Responsable Municipio") {
                $('#state_id, #municipality_id').prop('disabled', false);
            } else {
                $('#state_id, #municipality_id').prop('disabled', true);
            }
        }
    }

    // Deshabilitar selectores según el rol
    if ($('#isResponsableRadar').val() === '1') {
        handleRoleSelectors();
    }

    $('#rol_id').change(function() {
        handleRoleSelectors();
    });

    // Deshabilitar selectores de Tipo de reporte si el usuario tiene el rol "Responsable Municipio"
    if ($('#isResponsableMunicipio').val() === '1') {
        $('#report_type_id').prop('disabled', true);
    }

    // Deshabilitar selectores de Estado y Municipio si el usuario tiene el rol "Responsable Dependencia"
    if ($('#isResponsableDependencia').val() === '1') {
        $('#state_id, #municipality_id').prop('disabled', true);
    }

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
                required: true,
            },
            rol_id: {
                required: true,
            },
            status: {
                required: true,
            },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.test').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
