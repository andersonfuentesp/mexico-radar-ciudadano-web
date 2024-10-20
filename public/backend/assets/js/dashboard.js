document.addEventListener('DOMContentLoaded', function () {
    // Obtener los datos desde los inputs ocultos
    const barData = JSON.parse(document.getElementById('barChartData').value);
    const pieData = JSON.parse(document.getElementById('pieChartData').value);
    const rolesData = JSON.parse(document.getElementById('rolesChartData').value);
    const totalUsers = parseInt(document.getElementById('totalUsers').value); // Obtenemos el valor total de usuarios

    initializeDarkModeObserver(barData, pieData, rolesData, totalUsers);

    // Inicializamos los gráficos
    updateChart('barChartContainer', barData, 'column');
    updateChart('pieChartContainer', pieData, 'pie');
    updateChart('rolesChartContainer', rolesData, 'pie');

    // Inicializamos el gráfico de columna (usuarios)
    updateColumnChart('usersColumnChart', totalUsers);
});

function updateChart(containerId, data, chartType) {
    const isDarkMode = document.body.classList.contains('dark-mode');
    const backgroundColor = isDarkMode ? '#303030' : '#FFFFFF';
    const textColor = isDarkMode ? '#C0C0C0' : '#333333';

    Highcharts.chart(containerId, {
        chart: {
            type: chartType,
            backgroundColor: backgroundColor,
            borderRadius: 10,
            shadow: true,
            plotShadow: true,
            style: {
                fontFamily: 'Poppins, sans-serif'
            }
        },
        title: {
            text: chartType === 'column' ? 'Cantidad de Servicios por Municipio' : 
                   (containerId === 'rolesChartContainer' ? 'Distribución de Usuarios por Rol' : 'Distribución de Servicios por Municipio'),
            style: {
                color: textColor,
                fontWeight: 'bold',
                fontSize: '20px',
            }
        },
        xAxis: chartType === 'column' ? {
            categories: data.categories,
            labels: {
                style: {
                    color: textColor,
                    fontSize: '14px'
                }
            }
        } : undefined,
        yAxis: chartType === 'column' ? {
            title: {
                text: 'Cantidad de Servicios',
                style: {
                    color: textColor,
                    fontSize: '16px'
                }
            },
            labels: {
                style: {
                    color: textColor,
                    fontSize: '12px'
                }
            },
            gridLineColor: isDarkMode ? '#444' : '#E0E0E0'
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
                },
                showInLegend: true
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: {
                        color: textColor,
                        fontWeight: 'bold'
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
                color: textColor,
                fontWeight: 'bold',
                fontSize: '12px'
            }
        },
        tooltip: {
            backgroundColor: isDarkMode ? '#444' : '#F9F9F9',
            style: {
                color: textColor
            }
        }
    });
}

function updateColumnChart(containerId, totalUsers) {
    const isDarkMode = document.body.classList.contains('dark-mode');
    const backgroundColor = isDarkMode ? '#303030' : '#FFFFFF'; // Control del fondo
    const textColor = isDarkMode ? '#C0C0C0' : '#333333'; // Control del texto

    Highcharts.chart(containerId, {
        chart: {
            type: 'column',
            backgroundColor: backgroundColor, // Asignar el color de fondo basado en el modo
            borderRadius: 10, // Mantener el borde redondeado
            shadow: true // Mantener la sombra
        },
        title: {
            text: 'Total de Usuarios',
            style: {
                color: textColor, // Asignar el color del texto basado en el modo
                fontSize: '20px',
                fontWeight: 'bold'
            }
        },
        xAxis: {
            categories: ['Usuarios'],
            labels: {
                style: {
                    color: textColor, // Asignar el color del texto basado en el modo
                    fontSize: '14px'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad',
                style: {
                    color: textColor, // Asignar el color del texto basado en el modo
                    fontSize: '16px'
                }
            },
            labels: {
                style: {
                    color: textColor, // Asignar el color del texto basado en el modo
                    fontSize: '12px'
                }
            },
            gridLineColor: isDarkMode ? '#444' : '#E0E0E0' // Ajustar color de la línea del grid
        },
        series: [{
            name: 'Total de Usuarios',
            data: [totalUsers],
            color: '#4CAF50' // Color de la barra
        }],
        credits: {
            enabled: false
        },
        legend: {
            enabled: false // No necesitamos leyenda en este gráfico
        },
        tooltip: {
            backgroundColor: isDarkMode ? '#444' : '#F9F9F9', // Ajustar el tooltip al modo
            style: {
                color: textColor
            }
        }
    });
}


function initializeDarkModeObserver(barData, pieData, rolesData, totalUsers) {
    new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === "class") {
                const currentClassState = document.body.classList.contains('dark-mode');
                if (typeof window.lastClassState === 'undefined' || currentClassState !== window.lastClassState) {
                    updateChart('barChartContainer', barData, 'column');
                    updateChart('pieChartContainer', pieData, 'pie');
                    updateChart('rolesChartContainer', rolesData, 'pie');
                    updateColumnChart('usersColumnChart', totalUsers);  // Actualizar el gráfico de usuarios
                }
                window.lastClassState = currentClassState;
            }
        });
    }).observe(document.body, {
        attributes: true
    });
}
