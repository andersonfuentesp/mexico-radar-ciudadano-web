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

        const contentString = `
            <div class="infowindow-content" style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                <h4 style="margin-top: 0; font-size: 16px; font-weight: bold; color: #555;">Detalles del Reporte</h4>
                <p><span style="font-weight: bold; color: #555;">Folio:</span> ${reportFolio}</p>
                <p><span style="font-weight: bold; color: #555;">Estado:</span> ${estadoNombre}</p>
                <p><span style="font-weight: bold; color: #555;">Municipio:</span> ${municipioNombre}</p>
                <p><span style="font-weight: bold; color: #555;">Colonia:</span> ${colonia}</p>
                <p><span style="font-weight: bold; color: #555;">Tipo de Reporte:</span> ${reportTypeName}</p>
                <p><span style="font-weight: bold; color: #555;">Dirección:</span> ${reportAddress}</p>
                <p><span style="font-weight: bold; color: #555;">Fecha del Reporte:</span> ${fechaReporte}</p>
            </div>
        `;

        const infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 300
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