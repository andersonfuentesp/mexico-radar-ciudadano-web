@extends('master')
@section('title', 'Detalle del Municipio')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded shadow-sm">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.municipalities') }}"><i
                                    class="fas fa-city"></i> Gestión de Municipios</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-info-circle"></i> Detalle del Municipio</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Municipio</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalles del Municipio:
                    {{ $municipiopol->MunicipioNombre }}</h3>
            </div>
            <div>
                <h3 class="card-title">Estado: {{ $municipiopol->EstadoNombre }}</h3>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title"><i class="fas fa-map"></i> Información del Municipio</h5><br>
                <div class="row">
                    <div class="col-md-2">
                        <p class="card-text"><b>ID del Municipio:</b> {{ $municipiopol->MunicipioPolId }}</p>
                    </div>
                    <div class="col-md-2">
                        <p class="card-text"><b>Número de Polígono:</b> {{ $municipiopol->MunicipioPolNumero }}</p>
                    </div>
                    <div class="col-md-2">
                        <p class="card-text"><b>Polígono:</b> {{ $municipiopol->MunicipioPolPoligono }}</p>
                    </div>
                </div>
            </div>
            <h4><i class="fas fa-map-marked-alt"></i> Mapa del Municipio</h4>
            <div id="map"
                style="height: 500px; width: 100%; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
        </div>

    </div>
@stop

@section('jscode')
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: {
                    lat: {{ $coordinates[0]['lat'] }},
                    lng: {{ $coordinates[0]['lng'] }}
                }
            });

            var municipioCoords = @json($coordinates);

            var municipioPolygon = new google.maps.Polygon({
                paths: municipioCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            municipioPolygon.setMap(map);
        }

        function loadGoogleMapsApi() {
            var script = document.createElement('script');
            script.src = '{{ route('maps.proxy') }}';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        document.addEventListener('DOMContentLoaded', loadGoogleMapsApi);
    </script>
@stop
