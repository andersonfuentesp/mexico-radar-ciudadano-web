document.addEventListener('DOMContentLoaded', function () {
    // Función para aplicar el modo oscuro
    function applyDarkMode() {
        document.body.classList.add('dark-mode');
    }

    // Función para aplicar el modo claro
    function applyLightMode() {
        document.body.classList.remove('dark-mode');
    }

    // Función para verificar y aplicar la preferencia de modo
    function applyStoredPreference() {
        const darkMode = localStorage.getItem('dark-mode');
        if (darkMode === 'enabled') {
            applyDarkMode();
        } else {
            applyLightMode();
        }
    }

    // Aplicar la preferencia almacenada al cargar la página
    applyStoredPreference();

    // Detectar cambios en el modo de color y guardar la preferencia
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (e.matches) {
            localStorage.setItem('dark-mode', 'enabled');
            applyDarkMode();
        } else {
            localStorage.setItem('dark-mode', 'disabled');
            applyLightMode();
        }
    });

    // Observador de mutación para detectar cambios en la clase del cuerpo
    new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === "class") {
                var currentClassState = document.body.classList.contains('dark-mode');
                if (typeof window.lastClassState === 'undefined' || currentClassState !== window.lastClassState) {
                    // Guardar el estado actual en localStorage
                    localStorage.setItem('dark-mode', currentClassState ? 'enabled' : 'disabled');
                }
                window.lastClassState = currentClassState;
            }
        });
    }).observe(document.body, {
        attributes: true
    });

    // Inicializar el estado del modo oscuro
    window.lastClassState = document.body.classList.contains('dark-mode');
});
