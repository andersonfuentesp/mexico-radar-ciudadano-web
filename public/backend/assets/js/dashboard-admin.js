document.addEventListener('DOMContentLoaded', function () {
    handleChartsChange(); // Llamar a la función al cargar el documento para inicializar los gráficos
});

function updateRolesChart() {
    const rolesData = JSON.parse(document.getElementById('rolesData').value);
    const backgroundColor = document.body.classList.contains('dark-mode') ? '#303030' : '#FFFFFF';
    const textColor = document.body.classList.contains('dark-mode') ? '#C0C0C0' : '#333333';

    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true,
            type: 'pie',
            backgroundColor: backgroundColor,
            style: {
                fontFamily: '"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif'
            }
        },
        title: {
            text: 'Porcentaje de usuarios por rol',
            style: {
                color: textColor,
                fontWeight: 'bold',
                fontSize: '20px'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
            style: {
                color: textColor,
                fontSize: '14px'
            },
            backgroundColor: backgroundColor === '#FFFFFF' ? 'rgba(255, 255, 255, 0.85)' : 'rgba(32, 32, 32, 0.85)',
            borderColor: 'lightgray',
            borderRadius: 10,
            borderWidth: 2
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}',
                    style: {
                        color: textColor,
                        fontSize: '13px'
                    },
                    connectorColor: 'silver'
                },
                showInLegend: true,
                depth: 35,
                innerSize: '50%',
                edgeColor: textColor,
                edgeWidth: 1
            }
        },
        legend: {
            itemStyle: {
                color: textColor, // Ensure text color in the legend is visible in both light and dark modes
                fontWeight: 'normal'
            },
            itemHoverStyle: {
                color: '#F0F0F0' // Lighter color on hover
            }
        },
        series: [{
            name: 'Usuarios',
            colorByPoint: true,
            data: rolesData,
            innerSize: '40%',
            startAngle: -90,
            endAngle: 270,
            borderRadius: 5
        }],
        credits: {
            enabled: false
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    symbolStroke: textColor,
                    theme: {
                        fill: backgroundColor
                    }
                }
            }
        }
    });
}

function updateOrdersStatusChart() {
    const ordersStatusData = JSON.parse(document.getElementById('ordersStatusData').value);
    const backgroundColor = document.body.classList.contains('dark-mode') ? '#303030' : '#FFFFFF';
    const textColor = document.body.classList.contains('dark-mode') ? '#C0C0C0' : '#333333';

    Highcharts.chart('ordersStatusChartContainer', {
        chart: {
            type: 'column',
            backgroundColor: backgroundColor
        },
        title: {
            text: 'Número de pedidos por estado',
            style: {
                color: textColor,
                fontWeight: 'bold',
                fontSize: '20px'
            }
        },
        xAxis: {
            categories: ordersStatusData.map(item => item.name),
            labels: {
                style: {
                    color: textColor
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Pedidos',
                style: {
                    color: textColor
                }
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>',
            style: {
                color: textColor
            },
            backgroundColor: backgroundColor === '#FFFFFF' ? 'rgba(255, 255, 255, 0.85)' : 'rgba(32, 32, 32, 0.85)',
            borderColor: 'lightgray',
            borderRadius: 10,
            borderWidth: 2
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true,
                    style: {
                        color: textColor
                    }
                }
            }
        },
        series: [{
            name: 'Pedidos',
            data: ordersStatusData.map(item => ({
                name: item.name,
                y: item.y,
                color: item.color // Using the color passed from the backend
            })),
            colorByPoint: true
        }],
        credits: {
            enabled: false
        },
        exporting: {
            enabled: true
        }
    });
}

function handleChartsChange() {
    updateRolesChart();
    updateOrdersStatusChart();
}

new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
        if (mutation.attributeName === "class") {
            var currentClassState = document.body.classList.contains('dark-mode');
            if (typeof window.lastClassState === 'undefined' || currentClassState !== window.lastClassState) {
                handleChartsChange();
            }
            window.lastClassState = currentClassState;
        }
    });
}).observe(document.body, {
    attributes: true
});

window.lastClassState = document.body.classList.contains('dark-mode');