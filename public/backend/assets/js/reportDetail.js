document.addEventListener('DOMContentLoaded', function () {
    function initMap() {
        const gpsLat = document.getElementById('gpsLocation').value.split(',')[0];
        const gpsLng = document.getElementById('gpsLocation').value.split(',')[1];
        const estadoNombre = document.getElementById('estadoNombre').value;
        const municipioNombre = document.getElementById('municipioNombre').value;
        const colonia = document.getElementById('colonia').value;
        const reportTypeName = document.getElementById('reportTypeName').value;
        const fechaReporte = document.getElementById('reportedDate').value;
        const reportAddress = document.getElementById('reportAddress').value;
        const reportFolio = document.getElementById('reportFolio').value;
        const reportId = document.getElementById('reportId').value;

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: {
                lat: parseFloat(gpsLat),
                lng: parseFloat(gpsLng)
            }
        });

        // Mejor diseño más compacto para el contenido del infowindow
        const contentString = `
            <div class="infowindow-content" style="font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4;">
                <h4 style="margin: 0; font-size: 15px; font-weight: bold; color: #333;">Detalles del Reporte</h4>
                <p style="margin: 2px 0;"><strong>Folio:</strong> ${reportFolio}</p>
                <p style="margin: 2px 0;"><strong>Estado:</strong> ${estadoNombre}</p>
                <p style="margin: 2px 0;"><strong>Municipio:</strong> ${municipioNombre}</p>
                <p style="margin: 2px 0;"><strong>Colonia:</strong> ${colonia}</p>
                <p style="margin: 2px 0;"><strong>Tipo de Reporte:</strong> ${reportTypeName}</p>
                <p style="margin: 2px 0;"><strong>Dirección:</strong> ${reportAddress}</p>
                <p style="margin: 2px 0;"><strong>Fecha del Reporte:</strong> ${fechaReporte}</p>
            </div>
        `;

        const infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 280 // Ajustar el ancho máximo para que sea más compacto
        });

        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(gpsLat),
                lng: parseFloat(gpsLng)
            },
            map: map,
            icon: {
                url: document.getElementById('locationIconPath').value,
                scaledSize: new google.maps.Size(140, 140)
            },
            title: 'Ubicación del Reporte'
        });

        marker.addListener('click', function () {
            infowindow.open(map, marker);
        });

        map.setCenter(marker.getPosition());
    }

    const script = document.createElement('script');
    script.src = document.getElementById('mapsProxyRoute').value;
    script.async = true;
    script.defer = true;
    script.onload = initMap;
    document.head.appendChild(script);
});
