@extends('master')
@section('title', 'Radar ciudadano - Monitoreo')

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
        </div>
    </div>

@stop