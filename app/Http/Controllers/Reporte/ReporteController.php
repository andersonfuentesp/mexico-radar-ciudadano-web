<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

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
        // Obtener tipos de reporte y estatus de reporte para los selectores
        $reportTypes = DB::table('report_types')->pluck('ReportTypeName', 'ReportTypeId');
        $reportStatuses = DB::table('report_statuses')->pluck('report_status_name', 'report_status_id');

        // Consultar los municipios contratados que tengan el servicio "Listar reportes"
        $municipalities = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->where('municipality_services.service_name', 'Listar reportes')
            ->select(
                'contracted_municipalities.id',
                'contracted_municipalities.state_id',
                'contracted_municipalities.municipality_id',
                'contracted_municipalities.name as municipio',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                'contracted_municipalities.url as municipality_url', // URL del municipio
                'contracted_municipalities.token',
                'municipality_services.api_url as service_url',
                'municipality_services.method'
            )
            ->get();

        $reportsData = [];

        // Realizar peticiones a cada municipio
        foreach ($municipalities as $municipality) {
            $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');

            // Headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $municipality->token,
                'Content-Type' => 'application/json',
            ];

            // Parámetros para la consulta
            $params = [
                'estado_id' => $municipality->state_id,
                'municipio_id' => $municipality->municipality_id,
            ];

            // Realizar la solicitud HTTP (GET o POST)
            if (strtoupper($municipality->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $report) {
                        $reportsData[] = [
                            'report_id' => $report['report_id'],
                            'EstadoNombre' => $report['EstadoNombre'],
                            'MunicipioNombre' => $report['MunicipioNombre'],
                            'report_type' => $report['report_type_id'],
                            'neighborhood_name' => $report['NeighborhoodName'],
                            'report_address' => $report['report_address'],
                            'report_comment' => $report['report_comment'],
                            'report_status' => $report['ReportStatusName'],
                            'created_at' => $report['created_at'],
                            'municipality_url' => $municipality->municipality_url // Agregar la URL del municipio
                        ];
                    }
                }
            }
        }

        // Paginación usando LengthAwarePaginator
        $currentPage = $request->get('page', 1);
        $perPage = 100;
        $totalReports = count($reportsData);
        $paginatedReports = new LengthAwarePaginator(
            array_slice($reportsData, ($currentPage - 1) * $perPage, $perPage),
            $totalReports,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('app.reports.index', [
            'reports' => $paginatedReports,
            'reportTypes' => $reportTypes,
            'reportStatuses' => $reportStatuses,
        ]);
    }
}
