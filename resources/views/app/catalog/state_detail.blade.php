@extends('master')
@section('title', 'Detalle del Estado')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded shadow-sm">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.states') }}"><i class="fas fa-map"></i> Gestión de Estados</a></li>
                        <li class="breadcrumb-item active"><i class="fas fa-info-circle"></i> Detalle del Estado</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Estado</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detalles del Estado: {{ $state->EstadoNombre }}</h3>
        </div>

        <div class="card-body">
            <h4><i class="fas fa-map-marked-alt"></i> Mapa del Estado</h4>
            <div id="map" style="height: 500px; width: 100%; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"></div>
        </div>
    </div>
@stop

@section('jscode')
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: {lat: {{ $coordinates[0]['lat'] }}, lng: {{ $coordinates[0]['lng'] }}}
            });

            var stateCoords = @json($coordinates);

            var statePolygon = new google.maps.Polygon({
                paths: stateCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            statePolygon.setMap(map);
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
