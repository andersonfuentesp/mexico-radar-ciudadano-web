<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

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
        // Obtener todos los estados
        $states = Estado::all();
        $municipios = [];

        // Obtener tipos de reporte desde el servicio "Listar tipo de reporte"
        $reportTypes = [];

        try {
            // Consultar los municipios que tienen el servicio "Listar tipo de reporte"
            $municipalitiesForReportTypes = DB::table('contracted_municipalities')
                ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
                ->where('municipality_services.service_name', 'Listar tipo de reporte')
                ->select('contracted_municipalities.url as municipality_url', 'contracted_municipalities.token', 'municipality_services.api_url as service_url', 'municipality_services.method')
                ->get();

            foreach ($municipalitiesForReportTypes as $municipality) {
                $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');
                $headers = [
                    'Authorization' => 'Bearer ' . $municipality->token,
                    'Content-Type' => 'application/json',
                ];

                $response = strtoupper($municipality->method) === 'POST'
                    ? Http::withHeaders($headers)->post($apiUrl)
                    : Http::withHeaders($headers)->get($apiUrl);

                if ($response->successful()) {
                    $responseData = $response->json();
                    if (isset($responseData['data'])) {
                        foreach ($responseData['data'] as $reportType) {
                            $reportTypes[] = $reportType['ReportTypeName'];
                        }
                    }
                }
            }

            $reportTypes = array_unique($reportTypes);
        } catch (\Exception $e) {
            // Si ocurre un error, obtener los tipos de reporte desde la base de datos
            $reportTypes = DB::table('report_types')
                ->where('ReportTypeIsActive', true)
                ->orderBy('ReportTypeOrderPriority')
                ->pluck('ReportTypeName')
                ->toArray();
        }

        // Obtener estatus de reporte desde el servicio "Listar estatus de reporte"
        $reportStatuses = [];

        try {
            $municipalitiesForReportStatuses = DB::table('contracted_municipalities')
                ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
                ->where('municipality_services.service_name', 'Listar estatus de reporte')
                ->select('contracted_municipalities.url as municipality_url', 'contracted_municipalities.token', 'municipality_services.api_url as service_url', 'municipality_services.method')
                ->get();

            foreach ($municipalitiesForReportStatuses as $municipality) {
                $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');
                $headers = [
                    'Authorization' => 'Bearer ' . $municipality->token,
                    'Content-Type' => 'application/json',
                ];

                $response = strtoupper($municipality->method) === 'POST'
                    ? Http::withHeaders($headers)->post($apiUrl)
                    : Http::withHeaders($headers)->get($apiUrl);

                if ($response->successful()) {
                    $responseData = $response->json();
                    if (isset($responseData['data'])) {
                        foreach ($responseData['data'] as $reportStatus) {
                            $reportStatuses[] = $reportStatus['report_status_name'];
                        }
                    }
                }
            }

            $reportStatuses = array_unique($reportStatuses);
        } catch (\Exception $e) {
            // Si ocurre un error, obtener los estatus de reporte desde la base de datos
            $reportStatuses = DB::table('report_statuses')
                ->pluck('report_status_name')
                ->toArray();
        }

        // Construir la consulta base de reportes con orden por report_reported_time y respaldo en report_registration_time
        $reportsQuery = DB::table('reports')
            ->leftJoin('estado', 'reports.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('reports.state_id', '=', 'municipio.EstadoId')
                    ->on('reports.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('contracted_municipalities', function ($join) {
                $join->on('reports.state_id', '=', 'contracted_municipalities.state_id')
                    ->on('reports.municipality_id', '=', 'contracted_municipalities.municipality_id');
            })
            ->select(
                'reports.report_id',
                'reports.report_folio',
                'reports.state_id',
                'reports.municipality_id',
                'reports.report_registration_date',
                'reports.report_registration_time',
                'reports.report_reported_time',
                'reports.report_type_id',
                'reports.report_status_id',
                'reports.report_address',
                'reports.report_comment',
                'reports.created_at',
                'reports.is_contracted_municipality',
                'reports.generated_from',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                'contracted_municipalities.url as municipality_url'
            )
            ->orderByRaw('COALESCE(reports.report_reported_time, reports.report_registration_time) DESC'); // Usar el respaldo si es null

        if ($request->filled('estado_id')) {
            $reportsQuery->where('reports.state_id', $request->estado_id);
            $municipios = Municipio::where('EstadoId', $request->estado_id)->get();
        }

        if ($request->filled('municipio_id')) {
            $reportsQuery->where('reports.municipality_id', $request->municipio_id);
        }

        if ($request->filled('report_type')) {
            $reportsQuery->where('reports.report_type_id', $request->report_type);
        }

        if ($request->filled('search_status_name')) {
            $reportsQuery->where('reports.report_status_id', $request->search_status_name);
        }

        if ($request->filled('search_start') && $request->filled('search_end')) {
            $reportsQuery->whereBetween('reports.report_reported_time', [$request->search_start, $request->search_end]);
        }

        if ($request->filled('contratado')) {
            $reportsQuery->where('reports.is_contracted_municipality', $request->contratado);
        }

        $reports = $reportsQuery->paginate(100);

        if ($request->filled('estado_id') && $request->filled('municipio_id') && $reports->isEmpty()) {
            $isContracted = DB::table('contracted_municipalities')
                ->where('state_id', $request->estado_id)
                ->where('municipality_id', $request->municipio_id)
                ->exists();

            if (!$isContracted) {
                $notification = [
                    'message' => 'Municipio no está contratado, no se encontraron reportes.',
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($notification);
            }
        }

        return view('app.reports.index', [
            'reports' => $reports,
            'states' => $states,
            'municipios' => $municipios,
            'reportTypes' => $reportTypes,
            'reportStatuses' => $reportStatuses,
        ]);
    }

    public function reporteCiudadanoOld(Request $request)
    {
        // Obtener tipos de reporte desde el servicio "Listar tipo de reporte"
        $reportTypes = [];

        // Consultar los municipios que tienen el servicio "Listar tipo de reporte"
        $municipalitiesForReportTypes = DB::table('contracted_municipalities')
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->where('municipality_services.service_name', 'Listar tipo de reporte')
            ->select('contracted_municipalities.url as municipality_url', 'contracted_municipalities.token', 'municipality_services.api_url as service_url', 'municipality_services.method')
            ->get();

        foreach ($municipalitiesForReportTypes as $municipality) {
            $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');

            // Headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $municipality->token,
                'Content-Type' => 'application/json',
            ];

            // Realizar la solicitud HTTP (GET o POST) para obtener los tipos de reporte
            if (strtoupper($municipality->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl);
            }

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $reportType) {
                        $reportTypes[] = $reportType['ReportTypeName']; // Agregar solo los nombres
                    }
                }
            }
        }

        // Eliminar duplicados de reportTypes
        $reportTypes = array_unique($reportTypes);

        // Obtener estatus de reporte desde el servicio "Listar estatus de reporte"
        $reportStatuses = [];

        // Consultar los municipios que tienen el servicio "Listar estatus de reporte"
        $municipalitiesForReportStatuses = DB::table('contracted_municipalities')
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->where('municipality_services.service_name', 'Listar estatus de reporte')
            ->select('contracted_municipalities.url as municipality_url', 'contracted_municipalities.token', 'municipality_services.api_url as service_url', 'municipality_services.method')
            ->get();

        foreach ($municipalitiesForReportStatuses as $municipality) {
            $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');

            // Headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $municipality->token,
                'Content-Type' => 'application/json',
            ];

            // Realizar la solicitud HTTP (GET o POST) para obtener los estatus de reporte
            if (strtoupper($municipality->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl);
            }

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $reportStatus) {
                        $reportStatuses[] = $reportStatus['report_status_name']; // Agregar solo los nombres
                    }
                }
            }
        }

        // Eliminar duplicados de reportStatuses
        $reportStatuses = array_unique($reportStatuses);
        // Obtener todos los estados
        $states = Estado::all();
        $municipios = [];

        //######### Opción 1: En caso se reciba del grid de búsqueda

        // Verificar si el request tiene los filtros de búsqueda del grid (estado_id y municipio_id)
        if ($request->has('estado_id') && $request->has('municipio_id')) {
            $municipios = Municipio::where('EstadoId', $request->estado_id)->get();

            // Si los filtros están presentes, buscar el municipio contratado que tiene el servicio "Listar reportes"
            $municipality = DB::table('contracted_municipalities')
                ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
                ->leftJoin('municipio', function ($join) {
                    $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                        ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
                })
                ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
                ->where('municipality_services.service_name', 'Listar reportes')
                ->where('contracted_municipalities.state_id', $request->estado_id) // Filtrar por estado_id
                ->where('contracted_municipalities.municipality_id', $request->municipio_id) // Filtrar por municipio_id
                ->select(
                    'contracted_municipalities.id',
                    'contracted_municipalities.state_id',
                    'contracted_municipalities.municipality_id',
                    'estado.EstadoNombre',
                    'municipio.MunicipioNombre',
                    'contracted_municipalities.url as municipality_url', // URL del municipio
                    'contracted_municipalities.token',
                    'municipality_services.api_url as service_url',
                    'municipality_services.method'
                )
                ->first(); // Obtenemos un solo resultado

            // Verificar si el municipio fue encontrado
            if (!$municipality) {
                $notification = [
                    'message' => 'Municipio contratado no encontrado o no tiene el servicio "Listar reportes".',
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($notification);
            }

            // Realizar la petición al servicio externo del municipio
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
                'search_start' => $request->search_start,
                'search_end' => $request->search_end,
                'search_status_name' => $request->search_status_name,
                'report_type' => $request->report_type,
            ];

            // Realizar la solicitud HTTP (GET o POST)
            if (strtoupper($municipality->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                $responseData = $response->json();

                // Recopilar los datos de reportes
                $reportsData = [];
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $report) {
                        $reportsData[] = [
                            'report_id' => $report['report_id'],
                            'state_id' => $report['state_id'],
                            'municipality_id' => $report['municipality_id'],
                            'EstadoNombre' => $report['EstadoNombre'],
                            'MunicipioNombre' => $report['MunicipioNombre'],
                            'report_type' => $report['ReportTypeName'], // Nombre del tipo de reporte
                            'neighborhood_name' => $report['NeighborhoodName'],
                            'report_address' => $report['report_address'],
                            'report_comment' => $report['report_comment'],
                            'report_status' => $report['ReportStatusName'],
                            'created_at' => $report['created_at'],
                            'municipality_url' => $municipality->municipality_url // Agregar la URL del municipio
                        ];
                    }
                }

                // Verificar si los reportes están en la tabla "reports" y asignar el campo 'is_contracted'
                $paginatedReports = collect($reportsData)->map(function ($report) {
                    $isContracted = Report::where('report_id', $report['report_id'])->exists();
                    $report['is_contracted'] = $isContracted ? 'Sí' : 'No';
                    return $report;
                });

                // Paginación usando LengthAwarePaginator
                $currentPage = $request->get('page', 1);
                $perPage = 100;
                $totalReports = count($reportsData);
                $paginatedReports = new LengthAwarePaginator(
                    array_slice($paginatedReports->toArray(), ($currentPage - 1) * $perPage, $perPage),
                    $totalReports,
                    $perPage,
                    $currentPage,
                    ['path' => $request->url(), 'query' => $request->query()]
                );

                // Retornar la vista con los datos de reportes paginados
                return view('app.reports.index', [
                    'reports' => $paginatedReports,
                    'reportTypes' => $reportTypes, // No necesitamos los tipos de reporte aquí
                    'reportStatuses' => $reportStatuses, // No necesitamos los estatus aquí
                    'states' => $states,
                    'municipios' => $municipios,
                ]);
            } else {
                // Si la respuesta no fue exitosa, manejar el error con una notificación
                $notification = [
                    'message' => 'No se pudo obtener la información de los reportes.',
                    'alert-type' => 'error',
                ];
                return redirect()->back()->with($notification);
            }
        }

        //######### Opción 2: En caso no se reciba del grid de búsqueda

        // Consultar los municipios contratados que tienen el servicio "Listar reportes"
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

        // Realizar peticiones a cada municipio para obtener los reportes ciudadanos
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
                //return response()->json($responseData);
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $report) {
                        $reportsData[] = [
                            'report_id' => $report['report_id'],
                            'state_id' => $report['state_id'],
                            'municipality_id' => $report['municipality_id'],
                            'EstadoNombre' => $report['EstadoNombre'],
                            'MunicipioNombre' => $report['MunicipioNombre'],
                            'report_type' => $report['ReportTypeName'], // Nombre del tipo de reporte
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

        // Verificar si los reportes están en la tabla "reports" y asignar el campo 'is_contracted'
        $paginatedReports = collect($reportsData)->map(function ($report) {
            $isContracted = Report::where('report_id', $report['report_id'])->exists();
            $report['is_contracted'] = $isContracted ? 'Sí' : 'No';
            return $report;
        });

        // Paginación usando LengthAwarePaginator
        $currentPage = $request->get('page', 1);
        $perPage = 100;
        $totalReports = count($reportsData);
        $paginatedReports = new LengthAwarePaginator(
            array_slice($paginatedReports->toArray(), ($currentPage - 1) * $perPage, $perPage),
            $totalReports,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        //return response()->json($reportStatuses);
        // Obtener todos los estados
        //$states = Estado::all();

        //return response()->json($paginatedReports);

        return view('app.reports.index', [
            'reports' => $paginatedReports,
            'reportTypes' => $reportTypes, // Tipos de reporte obtenidos desde el servicio sin duplicados
            'reportStatuses' => $reportStatuses, // Estatus de reporte obtenidos desde el servicio sin duplicados
            'states' => $states,
            'municipios' => $municipios,
        ]);
    }

    public function detailReporteCiudadano($report_id, $state_id, $municipality_id)
    {
        try {
            // Registrar los detalles de los parámetros desencriptados
            Log::info('Desencriptando los parámetros:', [
                'report_id' => $report_id,
                'state_id' => $state_id,
                'municipality_id' => $municipality_id
            ]);

            // Desencriptar los parámetros
            $reportId = decrypt($report_id);
            $stateId = decrypt($state_id);
            $municipalityId = decrypt($municipality_id);

            // 1. Buscar el reporte en la tabla "reports" por report_id
            $mobileReport = Report::where('report_id', $reportId)->first();

            // 2. Buscar el polígono del municipio usando el estado y municipio proporcionados
            $municipioPol = DB::table('municipiopol')
                ->where('EstadoPolId', $stateId)
                ->where('MunicipioPolId', $municipalityId)
                ->first();

            if (!$municipioPol) {
                Log::warning('No se encontró un municipio para el estado y municipio proporcionados.', [
                    'state_id' => $stateId,
                    'municipality_id' => $municipalityId
                ]);
                return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
            }

            // 3. Buscar en la tabla contracted_municipalities
            $contractedMunicipality = DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first();

            if (!$contractedMunicipality) {
                Log::warning('No se encontró un municipio contratado para las coordenadas proporcionadas.', [
                    'state_id' => $municipioPol->EstadoPolId,
                    'municipality_id' => $municipioPol->MunicipioPolId
                ]);

                // Retornar vista alternativa con datos mínimos del reporte
                return view('app.reports.detail_minimal', [
                    'mobileReport' => $mobileReport // Pasar los datos del reporte móvil si existen
                ]);
            }

            // 4. Buscar en la tabla municipality_services filtrando por service_name = 'Obtener datos de reporte'
            $municipalityService = DB::table('municipality_services')
                ->where('municipality_id', $contractedMunicipality->id)
                ->where('service_name', 'Obtener datos de reporte')
                ->first();

            if (!$municipalityService) {
                Log::warning('No se encontró un servicio para obtener los datos del reporte en el municipio.', [
                    'municipality_id' => $contractedMunicipality->id
                ]);
                return response()->json(['error' => 'No se encontró un servicio para obtener los datos del reporte en el municipio'], 404);
            }

            // 5. Concatenar la URL base del municipio con la API URL del servicio
            $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');
            Log::info('Concatenación de URL completada:', ['apiUrl' => $apiUrl]);

            // 6. Preparar los headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                'Content-Type' => 'application/json',
            ];

            // 7. Preparar los parámetros de la solicitud
            $params = [
                'report_id' => $reportId, // Agregar el report_id a los parámetros
            ];

            // 8. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            Log::info('Respuesta de la solicitud HTTP recibida.');

            // 9. Devolver la respuesta de la solicitud HTTP
            if ($response->successful()) {
                $report = $response->json()['data']; // Extraer los datos del reporte desde la respuesta

                // Devolver vista con datos del reporte y el posible reporte móvil
                return view('app.reports.detail', [
                    'report' => $report,
                    'mobileReport' => $mobileReport // Pasar los datos del reporte móvil si existen
                ]);
            }

            Log::error('Error en la solicitud HTTP, respuesta no exitosa.');
            return response()->json(['error' => 'No se pudo obtener la información del reporte'], 500);
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs
            Log::error('Error en detailReporteCiudadano: ' . $e->getMessage(), [
                'report_id' => $report_id,
                'state_id' => $state_id,
                'municipality_id' => $municipality_id
            ]);

            return response()->json([
                'message' => 'Hubo un error al obtener los detalles del reporte',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
