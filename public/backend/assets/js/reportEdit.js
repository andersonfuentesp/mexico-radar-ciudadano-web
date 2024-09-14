document.addEventListener('DOMContentLoaded', function() {
    function initMap() {
        const locationIconPath = document.getElementById('locationIconPath').value;
        const gpsLat = document.getElementById('gpsLat').value;
        const gpsLng = document.getElementById('gpsLng').value;
        const estadoNombre = document.getElementById('estadoNombre').dataset.nombre;
        const municipioNombre = document.getElementById('municipioNombre').dataset.nombre;
        const colonia = document.getElementById('colonia').value;
        const reportTypeName = document.getElementById('reportTypeName').dataset.nombre;
        const fechaReporte = document.getElementById('fechaReporte').value;

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: {
                lat: parseFloat(gpsLat),
                lng: parseFloat(gpsLng)
            }
        });

        const contentString = `
            <div class="infowindow-content">
                <h4>Detalles del Reporte</h4>
                <p><span>Estado:</span> ${estadoNombre}</p>
                <p><span>Municipio:</span> ${municipioNombre}</p>
                <p><span>Colonia:</span> ${colonia}</p>
                <p><span>Tipo de Reporte:</span> ${reportTypeName}</p>
                <p><span>Fecha del Reporte:</span> ${fechaReporte}</p>
            </div>
        `;

        const infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 300
        });

        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(gpsLat),
                lng: parseFloat(gpsLng)
            },
            map: map,
            icon: {
                url: locationIconPath,
                scaledSize: new google.maps.Size(140, 140)
            },
            title: 'Ubicación del Reporte'
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }

    const script = document.createElement('script');
    script.src = document.getElementById('mapsProxyUrl').value;
    script.async = true;
    script.defer = true;
    script.onload = initMap;
    document.head.appendChild(script);

    $('#fotografia_end').change(function(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#showImageEnd').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    $('#fotografia_report').change(function(e) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#showImageReport').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    // Configuración de Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Inicializar CKEditor y manejar instancias
    const editors = ['#direccion', '#comentario', '#response_text'];
    editors.forEach(selector => {
        ClassicEditor
            .create(document.querySelector(selector))
            .then(editor => {
                if (!window.editorInstances) {
                    window.editorInstances = {};
                }
                window.editorInstances[selector.replace('#', '')] = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });

    // Manejo del checkbox para agregar respuesta predeterminada
    $('#addResponseText').change(function() {
        if ($(this).is(':checked')) {
            const route = document.getElementById('responseDescriptionRoute').value;
            const stateId = document.getElementById('estadoNombre').value;
            const municipalityId = document.getElementById('municipioNombre').value;
            const reportTypeId = document.getElementById('reportTypeName').value;

            fetch(route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        state_id: stateId,
                        municipality_id: municipalityId,
                        report_type_id: reportTypeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.description) {
                        if (window.editorInstances && window.editorInstances['response_text']) {
                            window.editorInstances['response_text'].setData(data.description);
                        } else {
                            $('#response_text').val(data.description);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            if (window.editorInstances && window.editorInstances['response_text']) {
                window.editorInstances['response_text'].setData('');
            } else {
                $('#response_text').val('');
            }
        }
    });

    // Envío del formulario mediante Fetch API
    $('#reporteForm').on('submit', function(e) {
        e.preventDefault(); // Evitar el envío tradicional del formulario

        const form = $(this)[0];
        const formData = new FormData(form); // Crear un FormData con los datos del formulario

        // Deshabilitar el botón de envío para evitar múltiples envíos
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        // Limpiar mensajes anteriores
        toastr.clear();

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    const data = await response.json();
                    if (data.errors) {
                        // Mostrar mensajes de error específicos si existen
                        Object.keys(data.errors).forEach(key => {
                            data.errors[key].forEach(errorMessage => {
                                toastr.error(errorMessage);
                            });
                        });
                    } else {
                        // Mostrar mensaje de error general
                        throw new Error(data.message || 'Ocurrió un error desconocido');
                    }
                    throw new Error('Validation errors');
                }
                return response.json();
            })
            .then(data => {
                // Mostrar mensaje de éxito con toastr
                toastr.success(data.message);

                // Redirigir o actualizar la página si es necesario
                const redirectRoute = $('#reporteCiudadanoRoute').val();
                setTimeout(() => {
                    window.location.href = data.redirect || redirectRoute;
                }, 2000);
            })
            .catch(error => {
                if (error.message !== 'Validation errors') {
                    // Mostrar mensaje de error general si no es un error de validación
                    toastr.error(error.message);
                }
            })
            .finally(() => {
                // Rehabilitar el botón de envío
                submitButton.prop('disabled', false);
            });
    });
});