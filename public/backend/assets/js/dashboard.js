document.addEventListener('DOMContentLoaded', function () {
    // Obtener los datos desde los inputs ocultos
    const barData = JSON.parse(document.getElementById('barChartData').value);
    const pieData = JSON.parse(document.getElementById('pieChartData').value);

    initializeDarkModeObserver(barData, pieData);
    updateChart('barChartContainer', barData, 'column');
    updateChart('pieChartContainer', pieData, 'pie');
});

function updateChart(containerId, data, chartType) {
    const isDarkMode = document.body.classList.contains('dark-mode');
    const backgroundColor = isDarkMode ? '#303030' : '#FFFFFF';
    const textColor = isDarkMode ? '#C0C0C0' : '#333333';

    Highcharts.chart(containerId, {
        chart: {
            type: chartType,
            backgroundColor: backgroundColor,
            borderRadius: 10, // Bordes redondeados para más estilo
        },
        title: {
            text: chartType === 'column' ? 'Cantidad de Servicios por Municipio' : 'Distribución de Servicios por Municipio',
            style: {
                color: textColor,
                fontWeight: 'bold',
                fontSize: '18px' // Aumentar tamaño de fuente del título
            }
        },
        xAxis: chartType === 'column' ? {
            categories: data.categories,
            labels: {
                style: {
                    color: textColor
                }
            }
        } : undefined,
        yAxis: chartType === 'column' ? {
            title: {
                text: 'Cantidad de Servicios',
                style: {
                    color: textColor
                }
            },
            labels: {
                style: {
                    color: textColor
                }
            }
        } : undefined,
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: textColor,
                        fontSize: '14px'
                    }
                }
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: {
                        color: textColor
                    }
                }
            }
        },
        series: data.series,
        credits: {
            enabled: false
        },
        legend: {
            enabled: true,
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            itemStyle: {
                color: textColor
            }
        }
    });
}

function initializeDarkModeObserver(barData, pieData) {
    new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === "class") {
                const currentClassState = document.body.classList.contains('dark-mode');
                if (typeof window.lastClassState === 'undefined' || currentClassState !== window.lastClassState) {
                    // Si el estado de modo oscuro cambió, actualizar los gráficos
                    updateChart('barChartContainer', barData, 'column');
                    updateChart('pieChartContainer', pieData, 'pie');
                }
                window.lastClassState = currentClassState;
            }
        });
    }).observe(document.body, {
        attributes: true
    });
}
