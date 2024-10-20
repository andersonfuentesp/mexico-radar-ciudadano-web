document.addEventListener('DOMContentLoaded', function () {
    let selectedUrl = '';
    let selectedMethod = '';
    let selectedToken = '';
    let selectedIndex = '';

    // Abrir modal al hacer click en "Probar Conectividad"
    document.querySelectorAll('.test-connection-button').forEach((button) => {
        button.addEventListener('click', function () {
            selectedUrl = this.dataset.url;
            selectedMethod = this.dataset.method;
            selectedToken = this.dataset.token; // Capturamos el token
            selectedIndex = this.dataset.index; // Capturamos el índice de la fila

            document.getElementById('modal-service-url').textContent = selectedUrl;
            $('#connectionModal').modal('show');
        });
    });

    // Agregar nueva fila de key-value para los parámetros
    document.querySelector('.btn-add-param').addEventListener('click', function () {
        const paramRow = `<div class="form-group row">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Key" name="key[]" />
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Value" name="value[]" />
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-remove-param">-</button>
            </div>
        </div>`;
        document.getElementById('params-container').insertAdjacentHTML('beforeend', paramRow);
    });

    // Eliminar fila de parámetros
    document.getElementById('params-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove-param')) {
            event.target.closest('.form-group').remove();
        }
    });

    // Confirmar prueba de conexión en el modal
    document.getElementById('test-connection-confirm').addEventListener('click', function () {
        const keys = document.querySelectorAll('[name="key[]"]');
        const values = document.querySelectorAll('[name="value[]"]');
        let params = {};

        keys.forEach((key, index) => {
            const keyVal = key.value.trim();
            const valueVal = values[index].value.trim();

            if (keyVal) {
                params[keyVal] = valueVal;
            }
        });

        // Cerrar el modal
        $('#connectionModal').modal('hide');

        // Mostrar los valores en la consola para depuración
        console.log("URL antes: ", selectedUrl);
        console.log("Method: ", selectedMethod);
        console.log("Token: ", selectedToken); // Mostramos el token para depuración
        console.log("Params: ", params);

        // Si el método es GET, construimos la URL con los parámetros en formato query string
        if (selectedMethod === 'GET') {
            const queryString = new URLSearchParams(params).toString();
            selectedUrl = `${selectedUrl}?${queryString}`;
            console.log("URL con parámetros: ", selectedUrl);
        }

        // Mostrar el loader mientras se realiza la prueba de conectividad
        Swal.fire({
            title: 'Probando Conectividad...',
            text: 'Por favor espera mientras se prueba la conexión.',
            icon: 'info',
            allowOutsideClick: false,
            showCancelButton: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Probar la conectividad con fetch, utilizando el método dinámico de la tabla y el token
        fetch(selectedUrl, {
            method: selectedMethod, // Utilizar el método dinámico según la tabla
            headers: {
                'Authorization': `Bearer ${selectedToken}`, // Agregamos el token al header
                'Content-Type': 'application/json'
            },
            body: selectedMethod !== 'GET' ? JSON.stringify(params) : null
        })
            .then(response => {
                Swal.close();
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Error en la conexión');
            })
            .then(data => {
                // Mostrar el SweetAlert con opción de ver el JSON
                Swal.fire({
                    icon: 'success',
                    title: 'Conexión Exitosa',
                    text: 'La conexión con el servicio fue exitosa.',
                    showCancelButton: true,
                    confirmButtonText: 'Ver Respuesta',
                    cancelButtonText: 'Cerrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar el JSON en el modal formateado
                        const jsonResponse = JSON.stringify(data, null, 2); // Formato legible
                        document.getElementById('jsonResponseContent').textContent = jsonResponse;
                        $('#jsonResponseModal').modal('show');
                    }
                });

                // ** Crear un archivo JSON con los datos obtenidos **
                const jsonData = JSON.stringify(data, null, 2); // Serializar datos con formato legible
                const blob = new Blob([jsonData], { type: 'application/json' }); // Crear blob con tipo JSON
                const url = window.URL.createObjectURL(blob); // Generar URL para descargar el archivo

                // Crear un botón de descarga o activar uno existente basado en el índice
                const downloadLogButton = document.getElementById(`download-log-${selectedIndex}`);
                downloadLogButton.href = url;
                downloadLogButton.download = 'response_data.json'; // Asignar nombre del archivo
                downloadLogButton.style.display = 'inline-block'; // Mostrar el botón de descarga

                // También puedes simular una descarga automática si lo deseas
                downloadLogButton.click();
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo establecer la conexión con el servicio.',
                });

                const logData = `Error al intentar conectar con el servicio en: ${selectedUrl}\nMensaje de error: ${error.message}`;
                const blob = new Blob([logData], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);

                const downloadLogButton = document.getElementById(`download-log-${selectedIndex}`);
                downloadLogButton.href = url;
                downloadLogButton.download = 'log_conexion.txt';
                downloadLogButton.style.display = 'inline-block';
            });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Copiar el contenido JSON al portapapeles
    document.getElementById('copyJsonBtn').addEventListener('click', function () {
        const jsonContent = document.getElementById('jsonResponseContent').textContent;
        navigator.clipboard.writeText(jsonContent).then(function () {
            Swal.fire({
                icon: 'success',
                title: 'JSON Copiado',
                text: 'El contenido del JSON ha sido copiado al portapapeles.',
                timer: 2000
            });
        });
    });

    // Exportar JSON a CSV
    document.getElementById('exportToCSV').addEventListener('click', function () {
        const jsonContent = JSON.parse(document.getElementById('jsonResponseContent').textContent);
        const csv = convertJSONToCSV(jsonContent.data); // Usamos json.data
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'response_data.csv';
        a.click();
    });

    // Exportar JSON a XML
    document.getElementById('exportToXML').addEventListener('click', function () {
        const jsonContent = JSON.parse(document.getElementById('jsonResponseContent').textContent);
        const xml = convertJSONToXML(jsonContent.data); // Usamos json.data
        const blob = new Blob([xml], { type: 'application/xml' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'response_data.xml';
        a.click();
    });

    // Función para convertir JSON a CSV (aplana los objetos anidados)
    function convertJSONToCSV(json) {
        const csvRows = [];
        const headers = new Set(); // Conjunto para almacenar todas las llaves posibles

        // Aplana las estructuras y extrae todas las llaves únicas
        const flatData = json.map(row => flattenObject(row, '', headers));

        // Crear la cabecera
        csvRows.push([...headers].join(','));

        // Crear las filas
        flatData.forEach(flatRow => {
            const values = [...headers].map(header => `"${flatRow[header] || ''}"`);
            csvRows.push(values.join(','));
        });

        return csvRows.join('\n');
    }

    // Función para convertir JSON a XML
    function convertJSONToXML(json) {
        let xml = '<root>';

        json.forEach((row, index) => {
            xml += `<item id="${index + 1}">`;
            xml += convertObjectToXML(row);
            xml += '</item>';
        });

        xml += '</root>';
        return xml;
    }

    // Aplana un objeto (convierte estructuras anidadas en una sola línea con prefijos)
    function flattenObject(obj, parentKey = '', headers) {
        let flatObject = {};

        Object.keys(obj).forEach(key => {
            const fullKey = parentKey ? `${parentKey}.${key}` : key;

            if (typeof obj[key] === 'object' && obj[key] !== null && !Array.isArray(obj[key])) {
                Object.assign(flatObject, flattenObject(obj[key], fullKey, headers));
            } else {
                flatObject[fullKey] = obj[key];
                headers.add(fullKey); // Añadir la llave al conjunto de cabeceras
            }
        });

        return flatObject;
    }

    // Convierte un objeto en una estructura XML, respetando los anidamientos
    function convertObjectToXML(obj) {
        let xml = '';

        Object.keys(obj).forEach(key => {
            if (typeof obj[key] === 'object' && obj[key] !== null) {
                if (Array.isArray(obj[key])) {
                    obj[key].forEach(item => {
                        xml += `<${key}>${convertObjectToXML(item)}</${key}>`;
                    });
                } else {
                    xml += `<${key}>${convertObjectToXML(obj[key])}</${key}>`;
                }
            } else {
                xml += `<${key}>${obj[key] || ''}</${key}>`;
            }
        });

        return xml;
    }
});
