document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('change', (e) => {
            const id = e.target.dataset.id;
            const quantity = e.target.value;

            Swal.fire({
                title: 'Actualizando...',
                text: 'Por favor, espera.',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });

            fetch(`/app/default/clientes/carta/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ quantity })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => { // Espera 1.5 segundos antes de mostrar el mensaje de éxito
                            Swal.close();
                            toastr.success('Cantidad actualizada con éxito.');
                            setTimeout(() => { // Espera un breve momento para recargar
                                location.reload();
                            }, 200); // Tiempo breve para que el usuario vea el mensaje
                        }, 200); // Tiempo para que el loader sea visible
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.close();
                    toastr.error('Error al actualizar la cantidad.');
                });
        });
    });

    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function (e) {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor, espera.',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });

            fetch(`/app/default/clientes/carta/delete/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify()
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => { // Espera 1.5 segundos antes de mostrar el mensaje de éxito
                            Swal.close();
                            toastr.success('Producto eliminado con éxito.');
                            setTimeout(() => { // Recarga inmediatamente después del mensaje de éxito
                                location.reload();
                            }, 200); // Tiempo breve para que el usuario vea el mensaje
                        }, 200); // Loader visible durante 1.5 segundos
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.close();
                    toastr.error('Error al eliminar el producto.');
                });
        });
    });

    document.querySelector('.realizar-pedido').addEventListener('click', () => {
        Swal.fire({
            title: 'Completa tu pedido',
            html: `
                <label for="payment-method" style="display: block; margin-top: 10px;">Método de Pago:</label>
                <select id="payment-method" class="swal2-input">
                    <option value="">Selecciona un método de pago</option>
                    <option value="yape">Yape</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="efectivo">Efectivo</option>
                </select>
    
                <label for="delivery-zone" style="display: block; margin-top: 10px;">Zona de Entrega:</label>
                <select id="delivery-zone" class="swal2-input">
                    <option value="">Selecciona una zona de entrega</option>
                    <option value="zona1">Zona 1: Cobertura en el mismo Chilca. Costo: S/1.00.</option>
                    <option value="zona2">Zona 2: Cobertura extendida alrededor de Chilca, incluyendo áreas residenciales y comerciales. Costo: S/2.50.</option>
                    <option value="zona3">Zona 3: Cobertura en 15 de Enero, Papa León, Olof Palme, Benjamin, Salinas, Playa Yaya, Playa San Pedro. Costo: S/3.00.</option>
                </select>
    
                <label for="reference" style="display: block; margin-top: 10px;">Referencia:</label>
                <input id="reference" class="swal2-input" placeholder="Ej. Número de operación bancaria">
    
                <label for="notes" style="display: block; margin-top: 10px;">Notas del Pedido:</label>
                <textarea id="notes" class="swal2-textarea" placeholder="Detalles adicionales sobre tu pedido"></textarea>
            `,
            width: '800px',
            focusConfirm: false,
            preConfirm: () => {
                const paymentMethod = document.getElementById('payment-method').value;
                const deliveryZone = document.getElementById('delivery-zone').value;
                const reference = document.getElementById('reference').value.trim();
                const notes = document.getElementById('notes').value.trim();

                if (!paymentMethod || !deliveryZone || !reference || !notes) {
                    Swal.showValidationMessage('Por favor, completa todos los campos antes de continuar.');
                    return false;
                }

                return {
                    paymentMethod: paymentMethod,
                    deliveryZone: deliveryZone,
                    reference: reference,
                    notes: notes
                }
            },
            confirmButtonText: 'Realizar Pedido',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                sendOrder(result.value);
            }
        });
    });

    function sendOrder(orderDetails) {
        Swal.fire({
            title: 'Procesando pedido...',
            text: 'Por favor, espera.',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
        });

        fetch('/app/default/clientes/carta/place-order', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(orderDetails)
        })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                toastr.error('Error al procesar el pedido.');
            });
    }

});