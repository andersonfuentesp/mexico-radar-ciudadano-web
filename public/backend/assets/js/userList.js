$(document).ready(function() {
    if ($('#isResponsableRadar').val() === '1') {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Acción no permitida',
                text: 'No tienes permiso para eliminar usuarios.',
                confirmButtonText: 'Entendido'
            });
        });
    }
});