// public/backend/assets/js/directoryFormEdit.js

$(document).ready(function() {
    // Inicializar componentes
    bsCustomFileInput.init();
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Cambio de estado para cargar municipios
    $('#StateId').change(function() {
        var estadoId = $(this).val();
        var municipioSelect = $('#MunicipalityId');
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

    // Mostrar vista previa de la imagen
    $('#DirectoryPhoto').change(function(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#showImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    // Funciones de validación
    function validatePhone(phone) {
        const phonePattern = /^[0-9]{10,15}$/;
        return phonePattern.test(phone);
    }

    function validateEmail(email) {
        return email.includes('@');
    }

    function validateWeb(url) {
        const webPattern = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([/\w .-]*)*\/?$/;
        return webPattern.test(url);
    }

    function validateImage(image) {
        const imagePattern = /\.(jpg|jpeg|png|tiff)$/i;
        return imagePattern.test(image);
    }

    function validateRequired(field, fieldName) {
        if (!field || field.trim() === '') {
            toastr.error(`${fieldName} es obligatorio.`);
            return false;
        }
        return true;
    }

    // Validación al enviar el formulario
    $('form').submit(function(e) {
        let valid = true;

        // Validar teléfono 1
        const phone1 = $('input[name="DirectoryPhone01"]').val();
        if (!validatePhone(phone1)) {
            toastr.error('Teléfono 1 no es válido. Debe contener entre 10 y 15 dígitos.');
            valid = false;
        }

        // Validar teléfono 2 (opcional)
        const phone2 = $('input[name="DirectoryPhone02"]').val();
        if (phone2 && !validatePhone(phone2)) {
            toastr.error('Teléfono 2 no es válido. Debe contener entre 10 y 15 dígitos.');
            valid = false;
        }

        // Validar email
        const email = $('input[name="DirectoryEmail"]').val();
        if (!validateEmail(email)) {
            toastr.error('Email no es válido.');
            valid = false;
        }

        // Validar sitio web (opcional)
        const web = $('input[name="DirectoryWeb"]').val();
        if (web && !validateWeb(web)) {
            toastr.error('Sitio Web no es válido.');
            valid = false;
        }

        // Validar foto (opcional)
        const photo = $('input[name="DirectoryPhoto"]').val();
        if (photo && !validateImage(photo)) {
            toastr.error('Formato de foto no es válido. Debe ser JPG, JPEG, PNG o TIFF.');
            valid = false;
        }

        // Validar campos obligatorios
        const extension = $('input[name="DirectoryExtension"]').val();
        if (!validateRequired(extension, 'Extensión')) valid = false;

        const schedule = $('input[name="DirectorySchedule"]').val();
        if (!validateRequired(schedule, 'Horario')) valid = false;

        const daysAttention = $('input[name="DirectoryDaysAttention"]').val();
        if (!validateRequired(daysAttention, 'Días de Atención')) valid = false;

        const responsible = $('input[name="DirectoryResponsible"]').val();
        if (!validateRequired(responsible, 'Responsable')) valid = false;

        const position = $('input[name="DirectoryPosition"]').val();
        if (!validateRequired(position, 'Cargo')) valid = false;

        if (!valid) {
            e.preventDefault();
        }
    });
});
