<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin.reports.all')->only('reporteCiudadano');
        $this->middleware('can:admin.reports.add')->only('addReporteCiudadano', 'storeReporteCiudadano');
        $this->middleware('can:admin.reports.edit')->only('editReporteCiudadano', 'updateReporteCiudadano');
        $this->middleware('can:admin.reports.detail')->only('detailReporteCiudadano');
        $this->middleware('can:admin.reports.delete')->only('deleteReporteCiudadano');
    }

    public function reporteCiudadano(Request $request)
    {
        $user = auth()->user();
        $userRoles = $user->roles->pluck('name')->toArray();

        $query = DB::table('reports')
            ->leftJoin('estado', 'reports.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('reports.state_id', '=', 'municipio.EstadoId')
                    ->on('reports.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('report_statuses', 'reports.report_status_id', '=', 'report_statuses.report_status_id')
            ->leftJoin('neighborhoods', function ($join) {
                $join->on('reports.state_id', '=', 'neighborhoods.state_id')
                    ->on('reports.municipality_id', '=', 'neighborhoods.municipality_id')
                    ->on('reports.neighborhood_id', '=', 'neighborhoods.neighborhood_id');
            })
            ->leftJoin('report_types', 'reports.report_type_id', '=', 'report_types.ReportTypeId')
            ->select(
                'reports.*',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                'report_statuses.report_status_name as ReportStatusName',
                'neighborhoods.neighborhood_name as NeighborhoodName',
                'report_types.ReportTypeName'
            )
            ->orderByDesc('reports.created_at');

        // Filtros de búsqueda
        if ($request->has('search_estado')) {
            $query->where('estado.EstadoNombre', 'like', '%' . $request->search_estado . '%');
        }

        if ($request->has('search_municipio')) {
            $query->where('municipio.MunicipioNombre', 'like', '%' . $request->search_municipio . '%');
        }

        if ($request->has('report_type')) {
            $query->where('reports.report_type_id', $request->report_type);
        }

        if ($request->has('search_vigencia_inicial') && !empty($request->search_vigencia_inicial)) {
            $query->whereDate('reports.created_at', '>=', $request->search_vigencia_inicial);
        }

        if ($request->has('search_vigencia_final') && !empty($request->search_vigencia_final)) {
            $query->whereDate('reports.created_at', '<=', $request->search_vigencia_final);
        }

        if ($request->has('report_status')) {
            $query->where('reports.report_status_id', $request->report_status);
        }

        // Opción para filtrar si el municipio es contratado o no
        if ($request->has('municipio_contratado')) {
            if ($request->municipio_contratado == '1') {
                $query->whereNotNull('reports.municipio_id');
            } else {
                $query->whereNull('reports.municipio_id');
            }
        }

        // Paginación
        $reports = $query->paginate(10);

        // Obtener tipos de reporte y estatus de reporte para los selectores
        $reportTypes = DB::table('report_types')->pluck('ReportTypeName', 'ReportTypeId');
        $reportStatuses = DB::table('report_statuses')->pluck('report_status_name', 'report_status_id');

        return view('app.reportes.index', compact('reports', 'reportTypes', 'reportStatuses', 'userRoles'));
    }
}
