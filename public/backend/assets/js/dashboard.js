let chartData = JSON.parse(document.getElementById('chartData').value);
let tiposChartData = JSON.parse(document.getElementById('tiposChartData').value);

// Función para actualizar el gráfico
function updateRolesChart() {
    const isDarkMode = document.body.classList.contains('dark-mode');
    const backgroundColor = isDarkMode ? '#303030' : '#FFFFFF';
    const textColor = isDarkMode ? '#C0C0C0' : '#333333';

    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            backgroundColor: backgroundColor, // Fondo del gráfico
        },
        title: {
            text: 'Porcentaje de usuarios por rol',
            style: {
                color: textColor, // Color del texto del título
                fontWeight: 'bold',
                fontSize: '16px'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: 'usuarios'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y} usuarios'
                }
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: chartData // Usamos chartData directamente aquí.
        }],
        credits: {
            enabled: false
        }
    });
}

function updateTiposOperacionChart() {
    const isDarkMode = document.body.classList.contains('dark-mode');
    const backgroundColor = isDarkMode ? '#303030' : '#FFFFFF';
    const textColor = isDarkMode ? '#C0C0C0' : '#333333';

    Highcharts.chart('operacionesTipoContainer', {
        chart: {
            type: 'bar',
            backgroundColor: backgroundColor,
            zoomType: 'y', // Permite hacer zoom en el eje Y
            scrollablePlotArea: {
                minWidth: 700, // Ajusta según necesidad
                scrollPositionX: 1
            }
        },
        title: {
            text: 'Expedientes por Tipo de Operación',
            style: {
                color: textColor
            }
        },
        xAxis: [{
            categories: tiposChartData.map(tipo => tipo.name),
            labels: {
                style: {
                    color: textColor
                }
            }
        }],
        yAxis: {
            min: 0,
            max: 10, // Establece un máximo visible que funcione para la paginación
            scrollbar: {
                enabled: true // Habilita el scrollbar en el eje Y
            },
            title: {
                text: 'Cantidad de Expedientes',
                style: {
                    color: textColor
                }
            },
            labels: {
                style: {
                    color: textColor
                }
            }
        },
        legend: {
            itemStyle: {
                color: textColor
            },
            reversed: true
        },
        plotOptions: {
            bar: {
                animation: true,
                dataLabels: {
                    enabled: true,
                    style: {
                        color: textColor
                    }
                }
            },
            series: {
                stacking: 'normal'
            }
        },
        tooltip: {
            valueSuffix: ' expedientes'
        },
        series: [{
            name: 'Expedientes',
            data: tiposChartData.map((tipo, i) => ({
                y: tipo.y,
                color: Highcharts.getOptions().colors[i % Highcharts.getOptions().colors
                    .length] // Cicla los colores disponibles
            })),
        }],
        credits: {
            enabled: false
        }
    });
}

// Esta función se llamará cuando la página se cargue y cuando se detecten cambios en las clases del body
function handleChartsChange() {
    updateRolesChart(); // Actualiza el gráfico de roles
    updateTiposOperacionChart(); // Actualiza el nuevo gráfico de tipos de operación
}

// Configura el observer para escuchar cambios en los atributos del body
new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
        if (mutation.attributeName === "class") {
            var currentClassState = document.body.classList.contains('dark-mode');
            // Verifica si el estado de 'dark-mode' ha cambiado
            if (typeof window.lastClassState === 'undefined' || currentClassState !== window
                .lastClassState) {
                handleChartsChange(); // Llama a la función solo si el modo oscuro ha cambiado
            }
            window.lastClassState =
                currentClassState; // Actualiza el estado conocido de 'dark-mode'
        }
    });
}).observe(document.body, {
    attributes: true
});

// Guarda el estado inicial de 'dark-mode'
window.lastClassState = document.body.classList.contains('dark-mode');

// Asegúrate de llamar a handleChartsChange cuando la página se carga
document.addEventListener('DOMContentLoaded', handleChartsChange);