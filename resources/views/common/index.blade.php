@extends('master')
@section('title', 'Radar Ciudadano - Monitoreo')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded shadow-sm">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    </ol>
                    <h1 class="m-0">
                        <b>Bienvenido, {{ Auth::user()->name }}!</b>
                    </h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <!-- Input oculto para pasar la cantidad total de usuarios -->
            <input type="hidden" id="totalUsers" value="{{ $totalUsers }}">

            <!-- Inputs ocultos para los gráficos -->
            <input type="hidden" id="barChartData" value="{{ $barData }}">
            <input type="hidden" id="pieChartData" value="{{ $pieData }}">
            <input type="hidden" id="rolesChartData" value="{{ $rolesData }}">

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

            <!-- Gráfico de roles y total de usuarios - Ocupa la pantalla -->
            <div class="row mt-4">
                <!-- Gráfico de roles - 50% -->
                <div class="col-md-6">
                    <div id="rolesChartContainer" style="height: 400px;"></div>
                </div>
                <!-- Gráfico de total de usuarios - 50%, con borde y sombra -->
                <div class="col-md-6">
                    <div class="card text-center" style="border-radius: 15px;">
                        <!-- Puedes quitar 'shadow-sm' si es redundante -->
                        <div class="card-body">
                            <div id="usersColumnChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('jscode')
    <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
@stop
