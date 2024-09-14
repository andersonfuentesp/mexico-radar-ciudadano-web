$(document).ready(function () {
    let map;
    let polygons = []; // Array para almacenar los polígonos
    let marker;

    $('#fotografia').change(function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#showImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    $('#estado').change(function () {
        const estadoId = $(this).val();
        const municipioSelect = $('#municipio');
        const municipiosRoute = $('#municipiosRoute').val();

        municipioSelect.html('<option value="">Seleccione un municipio</option>');

        if (estadoId) {
            fetch(`${municipiosRoute}/${estadoId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(municipio => {
                        municipioSelect.append(new Option(municipio.MunicipioNombre,
                            municipio.MunicipioId));
                    });
                    municipioSelect.prop('disabled', false);
                })
                .catch(error => console.error('Error:', error));
        }
    });

    $('#estado, #municipio').on('change', function () {
        const estadoId = $('#estado').val();
        const municipioId = $('#municipio').val();
        const getNextFolioRoute = $('#getNextFolio').val();

        if (estadoId && municipioId) {
            fetch(getNextFolioRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    state_id: estadoId,
                    municipality_id: municipioId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.next_folio) {
                        $('#folio').val(data.next_folio);
                    } else {
                        toastr.error('No se pudo obtener el número de folio.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('No se pudo obtener el número de folio.');
                });
        }
    });

    function drawPolygon(coordinates) {
        const bounds = new google.maps.LatLngBounds();
        const polygonCoords = coordinates.map(coord => {
            const latLng = new google.maps.LatLng(coord.lat, coord.lng);
            bounds.extend(latLng);
            return latLng;
        });

        // Limpiar todos los polígonos y marcadores anteriores
        polygons.forEach(p => p.setMap(null));
        polygons = []; // Reiniciar el array de polígonos
        if (marker) {
            marker.setMap(null);
        }

        if (!map) {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: bounds.getCenter()
            });
        } else {
            map.fitBounds(bounds);
        }

        // Crear un nuevo polígono con borde punteado
        const newPolygon = new google.maps.Polygon({
            paths: polygonCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.05, // Hacer el polígono más transparente
            clickable: true
        });

        newPolygon.setMap(map);
        polygons.push(newPolygon); // Añadir el nuevo polígono al array

        // Crear un Polyline para cada segmento del polígono
        polygonCoords.forEach((coord, index) => {
            const nextIndex = (index + 1) % polygonCoords.length;
            const segmentCoords = [polygonCoords[index], polygonCoords[nextIndex]];

            const polyline = new google.maps.Polyline({
                path: segmentCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: map,
                icons: [{
                    icon: {
                        path: 'M 0,-1 0,1',
                        strokeOpacity: 1,
                        scale: 3
                    },
                    offset: '0',
                    repeat: '10px'
                }]
            });

            polygons.push(polyline); // Añadir la línea al array de polígonos
        });

        // Añadir listener para seleccionar punto dentro del polígono
        google.maps.event.addListener(newPolygon, 'click', function (event) {
            const latLng = event.latLng;

            $('#ubicacionGps').val(latLng.lat() + ', ' + latLng.lng());
            toastr.success('Ubicación GPS actualizada: ' + latLng.lat() + ', ' + latLng.lng());

            // Si ya hay un marcador, eliminarlo
            if (marker) {
                marker.setMap(null);
            }

            // Crear un nuevo marcador con la imagen personalizada
            const iconPath = $('#locationIconPath').val();
            marker = new google.maps.Marker({
                position: latLng,
                map: map,
                icon: {
                    url: iconPath,
                    scaledSize: new google.maps.Size(140,
                        140), // Ajustar tamaño de la imagen
                }
            });

            // Hacer una solicitud fetch para obtener la colonia y el código postal
            const fetchColoniaCodigoPostalRoute = $('#fetchColoniaCodigoPostalRoute').val();

            if (latLng) {
                fetch(`${fetchColoniaCodigoPostalRoute}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Para autenticación en Laravel
                    },
                    body: JSON.stringify({
                        lat: latLng.lat(),
                        lng: latLng.lng()
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.colonia && data.codigoPostal) {
                            $('#colonia').val(data.colonia);
                            $('#codigoPostal').val(data.codigoPostal);
                        } else {
                            toastr.error('No se pudo obtener la colonia y el código postal.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

        });

        // Añadir listener para clics fuera del polígono
        google.maps.event.clearListeners(map, 'click'); // Limpiar listeners previos para evitar duplicados
        google.maps.event.addListener(map, 'click', function (event) {
            const isWithinPolygon = google.maps.geometry.poly.containsLocation(event.latLng,
                newPolygon);

            if (!isWithinPolygon) {
                toastr.clear(); // Limpiar los mensajes anteriores
                toastr.info('El punto seleccionado debe estar dentro del área delimitada.');
            }
        });
    }

    $('#buscarZona').click(function () {
        // Obtener valores de estado y municipio según el rol del usuario
        const estado = $('#estado').length > 0 ? $('#estado option:selected').text() : $('input[name="estado_nombre"]').val();
        const municipio = $('#municipio').length > 0 ? $('#municipio option:selected').text() : $('input[name="municipio_nombre"]').val();
        const referencia = $('#busqueda').val();
        const geocodeRoute = $('#geocodeRoute').val();

        const direccion = `${municipio}, ${estado}, ${referencia}`;
        //console.log(direccion);

        fetch(`${geocodeRoute}?address=${encodeURIComponent(direccion)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    toastr.info(data.error);
                    return;
                }

                // Actualizar los campos de Colonia y Código Postal
                $('#colonia').val(data.colonia || '');
                $('#codigoPostal').val(data.codigoPostal || '');

                drawPolygon(data.coordinates);
            })
            .catch(error => console.error('Error:', error));
    });

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {
                lat: 19.432608,
                lng: -99.133209
            } // Coordenadas de CDMX
        });
    }

    function loadGoogleMapsApi() {
        var script = document.createElement('script');

        // Obtener la ruta del input hidden
        var route = document.getElementById('mapsProxyRoute').value;

        script.src = route;
        script.async = true;
        script.defer = true;
        script.onload = initMap; // Aseguramos que initMap se llame una vez que el script se cargue
        document.head.appendChild(script);
    }

    loadGoogleMapsApi();

    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Configuración de CKEditor para textareas
    ClassicEditor.create(document.querySelector('#direccion')).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#comentario')).catch(error => console.error(error));

    $('#dependencia').on('change', function () {
        var dependenciaId = $(this).val();
        var reportTypeSelect = $('#reportType');
        var reportTypesRoute = $('#reportTypesRoute').val();

        if (dependenciaId) {
            $.ajax({
                url: reportTypesRoute,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({ dependency_id: dependenciaId }),
                success: function (data) {
                    reportTypeSelect.empty().append('<option value="">Seleccione un tipo de reporte</option>');
                    $.each(data, function (index, type) {
                        reportTypeSelect.append(`<option value="${type.value}">${type.label}</option>`);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            reportTypeSelect.empty().append('<option value="">Seleccione un tipo de reporte</option>');
        }
    });

    $('#reporteForm').on('submit', function (e) {
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
                const redirectRoute = $('#reporteCiudadanoRoute').val(); // Obtener la ruta de redirección
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