@extends('master')
@section('title', 'Gestión de Términos')

@section('content_header')
    <div class="card container-fluid mb-0">
        <div class="row mb-1 mt-3">
            <div class="col-sm-12">
                <div class="p-3 mb-2 bg-light rounded">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                        <li class="breadcrumb-item active">Gestión de Términos</li>
                    </ol>
                    <h1 class="m-0" style="font-size: 23px;"><b>Gestión de Términos</b></h1>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-book"></i> Lista de Términos</h3>
            @can('admin.catalog.terms.add')
                <a href="{{ route('admin.catalog.terms.add') }}" class="btn btn-custom float-right"><i class="fas fa-plus"></i>
                    Agregar Término</a>
            @endcan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.catalog.terms') }}">
                <div class="form-row align-items-center mb-4">
                    <div class="col-auto">
                        <label for="infrastructureFilter" class="col-form-label">Infraestructura</label>
                    </div>
                    <div class="col-auto">
                        <select name="infrastructure" id="infrastructureFilter" class="form-control">
                            <option value="">TODOS</option>
                            <option value="WEB" {{ request('infrastructure') == 'WEB' ? 'selected' : '' }}>WEB</option>
                            <option value="MÓVIL" {{ request('infrastructure') == 'MÓVIL' ? 'selected' : '' }}>MÓVIL
                            </option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </div>
            </form>
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Infraestructura</th>
                        <th>Documento</th>
                        <th>Tipo</th>
                        <th>Fecha de Última Actualización</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($terms as $key => $term)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $term->TermsId }}</td>
                            <td>{{ $term->TermsInfrastructure }}</td>
                            <td>
                                @if ($term->TermsDocumentPath)
                                    <a href="{{ $term->TermsDocumentPath }}" target="_blank" class="btn btn-secondary"><i
                                            class="fas fa-file-alt"></i> Ver Documento</a>
                                @else
                                    No hay documento
                                @endif
                            </td>
                            <td>{{ $term->TermsType }}</td>
                            <td>{{ \Carbon\Carbon::parse($term->TermsLastUpdateDate)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($term->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @can('admin.catalog.terms.detail')
                                    <a href="{{ route('admin.catalog.terms.detail', $term->TermsId) }}" class="btn btn-info sm"
                                        title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endcan

                                @can('admin.catalog.terms.edit')
                                    <a href="{{ route('admin.catalog.terms.edit', $term->TermsId) }}"
                                        class="btn btn-warning sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin.catalog.terms.delete')
                                    <form action="{{ route('admin.catalog.terms.delete', $term->TermsId) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger sm delete-button" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $terms->links() }}
            </div>
        </div>
    </div>
@stop

@section('jscode')
@endsection
