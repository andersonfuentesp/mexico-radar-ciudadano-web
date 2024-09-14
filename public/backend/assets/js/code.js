$(function () {
    $(document).on('click', '#delete', function (e) {
        e.preventDefault();
        var link = $(this).attr("href");

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link
                Swal.fire(
                    'Eliminado!',
                    'Tu registro ha sido borrado',
                    'success'
                )
            }
        })
    });

    // Confirmación de eliminación con envío de formulario
    $(document).on('click', '.delete-button', function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('form').submit();
            }
        });
    });

});
