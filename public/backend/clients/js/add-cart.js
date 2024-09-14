document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const formData = new FormData();
            formData.append('productId', productId);

            fetch('/app/default/clientes/carta/add', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Producto añadido al carrito');

                        let cartBadge = document.getElementById('cart-badge');

                        // Si no existe el badge, lo crea
                        if (!cartBadge) {
                            const navLink = document.querySelector('.fa-shopping-cart').parentNode;
                            if (navLink) {
                                // Crea el span del badge y lo añade
                                cartBadge = document.createElement('span');
                                cartBadge.id = 'cart-badge';
                                cartBadge.className = 'badge';
                                cartBadge.textContent = '1'; // Asume que se añade el primer ítem
                                navLink.appendChild(document.createTextNode(' ')); // Añade un espacio
                                navLink.appendChild(cartBadge);
                            }
                        } else {
                            // Si el badge ya existe, simplemente actualiza su conteo
                            const cartCount = parseInt(cartBadge.textContent) || 0;
                            cartBadge.textContent = cartCount + 1;
                        }
                    } else {
                        toastr.error('Error al añadir el producto al carrito');
                    }
                })
                .catch(error => console.error('Error:', error));

        });
    });
});
