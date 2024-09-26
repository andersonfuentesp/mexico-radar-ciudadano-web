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
            ->select(
                'reports.*',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                'report_statuses.report_status_name as ReportStatusName',
                'neighborhoods.neighborhood_name as NeighborhoodName',
                'report_dependencies.DependencyName'
            )
            ->orderByDesc('reports.created_at');

        // Filtros de búsqueda
        if ($request->has('search_estado')) {
            $query->where('estado.EstadoNombre', 'like', '%' . $request->search_estado . '%');
        }
        if ($request->has('search_municipio')) {
            $query->where('municipio.MunicipioNombre', 'like', '%' . $request->search_municipio . '%');
        }
        if ($request->has('search_dependencia')) {
            $query->where('report_dependencies.DependencyName', 'like', '%' . $request->search_dependencia . '%');
        }

        $reports = $query->paginate(10);

        return view('app.reportes.index', compact('reports', 'userRoles'));
    }
    
}
