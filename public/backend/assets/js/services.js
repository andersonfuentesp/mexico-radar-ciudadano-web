document.addEventListener('DOMContentLoaded', function () {
    let selectedUrl = '';
    let selectedMethod = '';
    let selectedToken = '';

    // Abrir modal al hacer click en "Probar Conectividad"
    document.querySelectorAll('.test-connection-button').forEach((button) => {
        button.addEventListener('click', function () {
            selectedUrl = this.dataset.url;
            selectedMethod = this.dataset.method;
            selectedToken = this.dataset.token; // Aquí capturamos el token
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
                Swal.fire({
                    icon: 'success',
                    title: 'Conexión Exitosa',
                    text: 'La conexión con el servicio fue exitosa.',
                });

                // ** Crear un archivo JSON con los datos obtenidos **
                const jsonData = JSON.stringify(data, null, 2); // Serializar datos con formato legible
                const blob = new Blob([jsonData], { type: 'application/json' }); // Crear blob con tipo JSON
                const url = window.URL.createObjectURL(blob); // Generar URL para descargar el archivo

                // Crear un botón de descarga o activar uno existente
                const downloadLogButton = document.getElementById(`download-log-0`);
                downloadLogButton.href = url;
                downloadLogButton.download = 'response_data.json'; // Asignar nombre del archivo
                downloadLogButton.style.display = 'inline-block'; // Mostrar el botón de descarga

                // También puedes simular una descarga automática si lo deseas
                downloadLogButton.click();
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo establecer la conexión con el servicio.',
                });

                const logData = `Error al intentar conectar con el servicio en: ${selectedUrl}\nMensaje de error: ${error.message}`;
                const blob = new Blob([logData], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);

                const downloadLogButton = document.getElementById(`download-log-0`);
                downloadLogButton.href = url;
                downloadLogButton.download = 'log_conexion.txt';
                downloadLogButton.style.display = 'inline-block';
            });
    });
});
