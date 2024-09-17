@extends('master')
@section('title', 'Agregar Término')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.catalog.terms') }}"><i class="fas fa-book"></i>
                                Gestión de Términos</a></li>
                        <li class="breadcrumb-item active"><a><i class="fas fa-plus"></i> Agregar Término</a></li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Agregar Término</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('csscode')
    <style>
        .ck-editor__editable_inline {
            min-height: 150px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title"><i class="fas fa-plus"></i> Datos del Término</h1>
        </div>

        <form class="form-horizontal" method="POST" action="{{ route('admin.catalog.terms.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group row">
                    <label for="TermsId" class="col-sm-2 col-form-label"><i class="fas fa-id-badge"></i> ID del
                        Término</label>
                    <div class="col-sm-10">
                        <input type="text" name="TermsId" class="form-control" value="{{ $uuid }}" readonly
                            required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TermsInfrastructure" class="col-sm-2 col-form-label"><i class="fas fa-building"></i>
                        Infraestructura</label>
                    <div class="col-sm-10">
                        <select name="TermsInfrastructure" class="form-control">
                            <option value="WEB">WEB</option>
                            <option value="MÓVIL">MÓVIL</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TermsContent" class="col-sm-2 col-form-label"><i class="fas fa-align-left"></i>
                        Contenido</label>
                    <div class="col-sm-10">
                        <textarea name="TermsContent" id="TermsContent" class="form-control" rows="5" placeholder="Ingrese el contenido"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TermsDocumentPath" class="col-sm-2 col-form-label"><i class="fas fa-file-alt"></i>
                        Documento</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="TermsDocumentPath" class="custom-file-input"
                                    id="TermsDocumentPath" accept="application/pdf" required>
                                <label class="custom-file-label" for="TermsDocumentPath">Seleccione archivo</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Subir</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TermsLastUpdateDate" class="col-sm-2 col-form-label"><i class="fas fa-calendar-alt"></i>
                        Fecha de Registro</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="TermsLastUpdateDate" class="form-control"
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TermsType" class="col-sm-2 col-form-label"><i class="fas fa-tags"></i> Tipo</label>
                    <div class="col-sm-10">
                        <select name="TermsType" class="form-control">
                            <option value="Términos y Condiciones">Términos y Condiciones</option>
                            <option value="Políticas de Privacidad">Políticas de Privacidad</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-custom"><i class="fas fa-save"></i> Guardar</button>
                <a href="{{ route('admin.catalog.terms') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i>
                    Regresar</a>
            </div>
        </form>
    </div>
@stop

@section('jscode')
    <script type="text/javascript">
        $(function() {
            ClassicEditor
                .create(document.querySelector('#TermsContent'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@stop
