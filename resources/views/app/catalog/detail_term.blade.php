@extends('master')
@section('title', 'Detalle del Término')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.terms') }}"><i class="fas fa-book"></i>
                                Gestión de Términos</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-eye"></i> Detalle del Término</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Detalle del Término</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-custom text-white">
            <h1 class="card-title"><i class="fas fa-eye"></i> Datos del Término</h1>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @foreach ([['ID del Término', 'TermsId', 'fas fa-id-badge'], ['Infraestructura', 'TermsInfrastructure', 'fas fa-building']] as $field)
                        <div class="form-group">
                            <label for="{{ $field[1] }}"><i class="{{ $field[2] }} mr-2"></i>
                                {{ $field[0] }}</label>
                            <div class="form-control">{{ $term->{$field[1]} }}</div>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="TermsContent"><i class="fas fa-align-left mr-2"></i> Contenido</label>
                        <div class="form-control" style="height: auto;">{!! $term->TermsContent !!}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    @foreach ([['Fecha de Última Actualización', 'TermsLastUpdateDate', 'fas fa-calendar-alt'], ['Tipo', 'TermsType', 'fas fa-tags']] as $field)
                        <div class="form-group">
                            <label for="{{ $field[1] }}"><i class="{{ $field[2] }} mr-2"></i>
                                {{ $field[0] }}</label>
                            <div class="form-control">{{ $term->{$field[1]} }}</div>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="TermsDocumentPath"><i class="fas fa-file-alt mr-2"></i> Documento</label>
                        @if ($term->TermsDocumentPath)
                            <iframe src="{{ $term->TermsDocumentPath }}" style="width: 100%; height: 400px;"
                                frameborder="0"></iframe>
                        @else
                            <div class="form-control">No hay documento adjunto</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('admin.catalog.terms') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i>
                Regresar</a>
            <button class="btn btn-custom" onclick="window.print()"><i class="fas fa-print"></i> Imprimir</button>
        </div>
    </div>
@stop
