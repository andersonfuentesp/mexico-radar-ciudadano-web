@extends('master')
@section('title', 'Radar Ciudadano - Monitoreo')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;">
                        <b>Bienvenido, {{ Auth::user()->name }}!</b>
                    </h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Inputs ocultos para pasar la data a los gráficos -->
            <input type="hidden" id="barChartData" value="{{ $barData }}">
            <input type="hidden" id="pieChartData" value="{{ $pieData }}">

            <!-- Diseño en dos columnas para los gráficos -->
            <div class="row">
                <!-- Gráfico de barras - 70% -->
                <div class="col-md-8">
                    <div id="barChartContainer" style="height: 400px;"></div>
                </div>
                <!-- Gráfico de pastel - 30% -->
                <div class="col-md-4">
                    <div id="pieChartContainer" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('jscode')
    <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
@stop
