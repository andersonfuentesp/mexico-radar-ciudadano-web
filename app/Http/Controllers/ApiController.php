<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Helpers\LocationHelper;
use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\MunicipioPol;
use App\Models\ReportType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    /**
     * Obtener todos los estados.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con la lista de estados.
     */
    public function getEstados()
    {
        $estados = Estado::all();
        return response()->json($estados);
    }

    /**
     * Obtener los municipios por estado.
     *
     * @param int $estadoId ID del estado.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los municipios del estado proporcionado.
     */
    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipio::where('EstadoId', $estadoId)->get();
        return response()->json($municipios);
    }

    /**
     * Obtener las noticias del municipio.
     *
     * @param Request $request Contiene los parámetros 'estado_id', 'municipio_id', y filtros opcionales de vigencia.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con las noticias o un error.
     * @throws \Illuminate\Validation\ValidationException Si los parámetros son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los parámetros.
     * 2. Busca el municipio en la tabla 'MunicipioPol'.
     * 3. Verifica el municipio contratado en 'contracted_municipalities'.
     * 4. Busca el servicio de 'Listar noticias'.
     * 5. Agrega filtros de vigencia si están presentes.
     * 6. Realiza la solicitud HTTP según el método (GET o POST).
     */
    public function getNoticias(Request $request)
    {
        // Validar que se haya enviado al menos uno de los parámetros
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
            'search_vigencia_inicial' => 'nullable|date',
            'search_vigencia_final' => 'nullable|date',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('MunicipioPol')
            ->where('EstadoPolId', $request->estado_id)
            ->where('MunicipioPolId', $request->municipio_id)
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['data' => [], 'message' => 'No se encontró un municipio contratado para el estado y municipio proporcionados.'], 200);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar noticias'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar noticias')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar noticias en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Preparar los headers con el token
        $headers = [
            'Authorization' => 'Bearer ' . $contractedMunicipality->token,
            'Content-Type' => 'application/json',
        ];

        // 6. Preparar los parámetros de la solicitud
        $params = [
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
        ];

        if ($request->has('search_vigencia_inicial') && !empty($request->search_vigencia_inicial)) {
            $params['search_vigencia_inicial'] = $request->search_vigencia_inicial;
        }

        if ($request->has('search_vigencia_final') && !empty($request->search_vigencia_final)) {
            $params['search_vigencia_final'] = $request->search_vigencia_final;
        }

        try {
            // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // 8. Verificar si la respuesta es exitosa
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['data' => [], 'message' => 'No se encontraron noticias disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getNoticias: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de noticias.'], 200);
        }
    }

    /**
     * Obtener los trámites del municipio.
     *
     * @param Request $request Contiene los parámetros 'estado_id' y 'municipio_id'.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los trámites o un error.
     * @throws \Illuminate\Validation\ValidationException Si los parámetros son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los parámetros.
     * 2. Busca el municipio en la tabla 'MunicipioPol'.
     * 3. Verifica el municipio contratado en 'contracted_municipalities'.
     * 4. Busca el servicio de 'Listar trámites'.
     * 5. Agrega filtros adicionales como 'dependencia' o 'nombre del trámite' si están presentes.
     * 6. Realiza la solicitud HTTP según el método (GET o POST).
     */
    public function getTramites(Request $request)
    {
        // Validar que se hayan enviado los parámetros obligatorios
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('MunicipioPol')
            ->where('EstadoPolId', $request->estado_id)
            ->where('MunicipioPolId', $request->municipio_id)
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['data' => [], 'message' => 'No se encontró un municipio contratado para el estado y municipio proporcionados.'], 200);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar tramites'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar tramites')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar trámites en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Preparar los headers con el token
        $headers = [
            'Authorization' => 'Bearer ' . $contractedMunicipality->token,
            'Content-Type' => 'application/json',
        ];

        // 6. Preparar los parámetros de la solicitud
        $params = [
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
        ];

        // Agregar los filtros de dependencia si están presentes
        if ($request->has('search_dependencia') && !empty($request->search_dependencia)) {
            $params['search_dependencia'] = $request->search_dependencia;
        }

        // Agregar los filtros de nombre del trámite si están presentes
        if ($request->has('search_tramite') && !empty($request->search_tramite)) {
            $params['search_tramite'] = $request->search_tramite;
        }

        try {
            // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // 8. Verificar si la respuesta es exitosa
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['data' => [], 'message' => 'No se encontraron trámites disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getTramites: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de trámites.'], 200);
        }
    }

    /**
     * Obtener los términos del municipio.
     *
     * @param Request $request Contiene los parámetros 'estado_id' y 'municipio_id'.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los términos o un error.
     * @throws \Illuminate\Validation\ValidationException Si los parámetros son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los parámetros.
     * 2. Busca el municipio en la tabla 'MunicipioPol'.
     * 3. Verifica el municipio contratado en 'contracted_municipalities'.
     * 4. Busca el servicio de 'Listar términos'.
     * 5. Realiza la solicitud HTTP según el método (GET o POST).
     */
    public function getTerms(Request $request)
    {
        // Validar que se hayan enviado los parámetros obligatorios
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('MunicipioPol')
            ->where('EstadoPolId', $request->estado_id)
            ->where('MunicipioPolId', $request->municipio_id)
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['data' => [], 'message' => 'No se encontró un municipio contratado para el estado y municipio proporcionados.'], 200);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar terminos'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar terminos')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar términos en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Preparar los headers con el token
        $headers = [
            'Authorization' => 'Bearer ' . $contractedMunicipality->token,
            'Content-Type' => 'application/json',
        ];

        // 6. Preparar los parámetros de la solicitud
        $params = [
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
        ];

        try {
            // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // 8. Verificar si la respuesta es exitosa
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['data' => [], 'message' => 'No se encontraron términos disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getTerms: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de términos.'], 200);
        }
    }

    /**
     * Obtener los números de emergencia del municipio.
     *
     * @param Request $request Contiene los parámetros 'estado_id' y 'municipio_id'.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los números de emergencia o un error.
     * @throws \Illuminate\Validation\ValidationException Si los parámetros son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los parámetros.
     * 2. Busca el municipio en la tabla 'MunicipioPol'.
     * 3. Verifica el municipio contratado en 'contracted_municipalities'.
     * 4. Busca el servicio de 'Listar números de emergencia'.
     * 5. Realiza la solicitud HTTP según el método (GET o POST).
     */
    public function getEmergencyNumbers(Request $request)
    {
        // Validar que se hayan enviado los parámetros obligatorios
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('MunicipioPol')
            ->where('EstadoPolId', $request->estado_id)
            ->where('MunicipioPolId', $request->municipio_id)
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['data' => [], 'message' => 'No se encontró un municipio contratado para el estado y municipio proporcionados.'], 200);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar numeros de emergencia'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar numeros de emergencia')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar números de emergencia en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Preparar los headers con el token
        $headers = [
            'Authorization' => 'Bearer ' . $contractedMunicipality->token,
            'Content-Type' => 'application/json',
        ];

        // 6. Preparar los parámetros de la solicitud
        $params = [
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
        ];

        try {
            // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // 8. Verificar si la respuesta es exitosa
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['data' => [], 'message' => 'No se encontraron números de emergencia disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getEmergencyNumbers: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de números de emergencia.'], 200);
        }
    }

    /**
     * Obtener el reporte del municipio basado en los parámetros proporcionados.
     *
     * @param Request $request La solicitud HTTP que contiene los parámetros 'estado_id' y 'municipio_id'.
     * 
     * @return \Illuminate\Http\JsonResponse Respuesta en formato JSON con el reporte o un mensaje de error.
     * 
     * @throws \Illuminate\Validation\ValidationException Si los parámetros obligatorios no se envían o son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los parámetros 'estado_id' y 'municipio_id'.
     * 2. Busca el polígono del municipio en la tabla 'MunicipioPol' usando los IDs de estado y municipio proporcionados.
     * 3. Verifica la existencia del municipio contratado en la tabla 'contracted_municipalities'.
     * 4. Busca el servicio de 'Listar reportes' en la tabla 'municipality_services'.
     * 5. Construye la URL del servicio concatenando la URL base del municipio con la API URL del servicio.
     * 6. Prepara los headers para la solicitud HTTP, incluyendo el token de autenticación.
     * 7. Prepara los parámetros para la solicitud, agregando filtros opcionales como 'search_fecha_inicio', 'search_fecha_fin' y 'search_tipo_reporte'.
     * 8. Realiza la solicitud HTTP a la URL generada, usando el método especificado (GET o POST) en 'municipality_services'.
     * 9. Devuelve la respuesta de la solicitud HTTP, ya sea el reporte en formato JSON o un mensaje de error.
     */
    public function getReport(Request $request)
    {
        // Validar que se hayan enviado los parámetros obligatorios
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('MunicipioPol')
            ->where('EstadoPolId', $request->estado_id)
            ->where('MunicipioPolId', $request->municipio_id)
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para el estado y municipio proporcionados'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['data' => [], 'message' => 'No se encontró un municipio contratado para el estado y municipio proporcionados.'], 200);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar reportes'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar reportes')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar reportes en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Preparar los headers con el token
        $headers = [
            'Authorization' => 'Bearer ' . $contractedMunicipality->token,
            'Content-Type' => 'application/json',
        ];

        // 6. Preparar los parámetros de la solicitud
        $params = [
            'estado_id' => $request->estado_id,
            'municipio_id' => $request->municipio_id,
        ];

        // Agregar filtros adicionales si están presentes en la solicitud
        if ($request->has('search_fecha_inicio') && !empty($request->search_fecha_inicio)) {
            $params['search_fecha_inicio'] = $request->search_fecha_inicio;
        }

        if ($request->has('search_fecha_fin') && !empty($request->search_fecha_fin)) {
            $params['search_fecha_fin'] = $request->search_fecha_fin;
        }

        if ($request->has('search_tipo_reporte') && !empty($request->search_tipo_reporte)) {
            $params['search_tipo_reporte'] = $request->search_tipo_reporte;
        }

        try {
            // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
            if (strtoupper($municipalityService->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // 8. Verificar si la respuesta es exitosa
            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['data' => [], 'message' => 'No se encontraron reportes disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getReport: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de reportes.'], 200);
        }
    }

    /**
     * Guardar un reporte con los datos proporcionados.
     *
     * @param Request $request Solicitud HTTP con los datos del reporte.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con el resultado de guardar el reporte.
     * @throws \Illuminate\Validation\ValidationException Si los datos son inválidos.
     * 
     * Funcionalidad:
     * 1. Valida los datos del reporte.
     * 2. Busca el polígono del municipio por las coordenadas.
     * 3. Guarda el reporte en la tabla `reports` del proyecto 1.
     * 4. Si el municipio es contratado, envía el reporte al proyecto 2 mediante una solicitud HTTP.
     */
    public function saveReport(Request $request)
    {
        try {
            Log::info('Datos del request:', $request->all());

            // Validación de los datos
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'report_type' => 'required|string|max:50',
                //'address' => 'nullable|string|max:1024',
                'comment' => 'nullable|string|max:90',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'gps_location' => 'nullable|string|max:50',
                'reported_photo' => 'nullable|file|image|max:2048',
                'end_photo' => 'nullable|file|image|max:2048',
                'attachments' => 'nullable|file|max:2048',
                'mobile_model' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'app_version' => 'nullable|string|max:50',
                'is_offline' => 'nullable|boolean',
                'network_type' => 'nullable|string|max:20',
                'imei' => 'nullable|string|max:20',
            ]);

            // Obtener la dirección usando las coordenadas si no se ha proporcionado
            if (empty($validated['address'])) {
                $validated['address'] = LocationHelper::obtenerDireccionCompleta($validated['latitude'], $validated['longitude']);
            }

            // Transacción para asegurar la concurrencia
            DB::transaction(function () use ($validated, $request) {

                // Consulta del polígono
                $municipioPol = DB::table('MunicipioPol')
                    ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                    ->first();

                if (!$municipioPol) {
                    throw new \Exception('No municipality found for the provided coordinates', 404);
                }

                // Buscar en la tabla contracted_municipalities
                $contractedMunicipality = DB::table('contracted_municipalities')
                    ->where('state_id', $municipioPol->EstadoPolId)
                    ->where('municipality_id', $municipioPol->MunicipioPolId)
                    ->first();

                // Generar el report_id internamente
                $generatedReportId = Str::uuid();

                // Obtener el último folio de la tabla `reports` y sumarle 1
                $lastFolio = DB::table('reports')->max('report_folio');
                $newFolio = $lastFolio ? $lastFolio + 1 : 100000; // Comenzar desde 100000 si no hay registros

                // Usar ImageHelper para almacenar imágenes
                $reportedPhotoPath = $request->hasFile('reported_photo')
                    ? ImageHelper::storeImage($request->file('reported_photo'), 'reports')
                    : null;

                $endPhotoPath = $request->hasFile('end_photo')
                    ? ImageHelper::storeImage($request->file('end_photo'), 'reports')
                    : null;

                $attachmentData = $request->hasFile('attachments')
                    ? ImageHelper::storeFile($request->file('attachments'), 'reports')
                    : null;

                // Registrar el reporte en la tabla `reports` del proyecto 1
                DB::table('reports')->insert([
                    'report_id' => $generatedReportId,
                    'report_folio' => $newFolio,
                    'report_registration_date' => now()->format('Y-m-d'),
                    'report_registration_time' => now()->format('Y-m-d H:i:s'),
                    'state_id' => $municipioPol->EstadoPolId,
                    'municipality_id' => $municipioPol->MunicipioPolId,
                    'report_type_id' => $validated['report_type'],
                    'report_status_id' => 'Reportado',
                    'report_address' => $validated['address'], // Dirección generada
                    'custom_report' => $validated['report_type'],
                    'report_comment' => $validated['comment'],
                    'gps_location' => $validated['gps_location'],
                    'geospatial_location' => DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)"),
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'is_contracted_municipality' => $contractedMunicipality ? 1 : 0,
                    'mobile_model' => $validated['mobile_model'],
                    'os_version' => $validated['os_version'],
                    'app_version' => $validated['app_version'],
                    'is_offline' => $validated['is_offline'],
                    'network_type' => $validated['network_type'],
                    'imei' => $validated['imei'],
                    'reported_photo' => $reportedPhotoPath,
                    'end_photo' => $endPhotoPath,
                    'attachments' => $attachmentData ? json_encode($attachmentData) : null,
                    'generated_from' => 'Móvil',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Si no es un municipio contratado, no enviar el reporte al proyecto 2
                if (!$contractedMunicipality) {
                    throw new \Exception('Report successfully registered, but does not belong to a contracted municipality');
                }

                // Buscar en la tabla municipality_services filtrando por service_name = 'Ingresar reporte'
                $municipalityService = DB::table('municipality_services')
                    ->where('municipality_id', $contractedMunicipality->id)
                    ->where('service_name', 'Ingresar reporte')
                    ->first();

                if (!$municipalityService) {
                    throw new \Exception('No service found to submit the report in the municipality', 404);
                }

                // Concatenar la URL base del municipio con la API URL del servicio
                $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

                // Preparar los headers con el token
                $headers = [
                    'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                    'Content-Type' => 'application/json',
                ];

                // Realizar la solicitud HTTP POST a la URL concatenada, incluyendo el report_id generado
                $response = Http::withHeaders($headers);

                // Adjuntar archivos si están presentes en la solicitud
                if ($request->hasFile('reported_photo')) {
                    $response = $response->attach(
                        'reported_photo',
                        $request->file('reported_photo'),
                        $request->file('reported_photo')->getClientOriginalName()
                    );
                }

                if ($request->hasFile('end_photo')) {
                    $response = $response->attach(
                        'end_photo',
                        $request->file('end_photo'),
                        $request->file('end_photo')->getClientOriginalName()
                    );
                }

                if ($request->hasFile('attachments')) {
                    $response = $response->attach(
                        'attachments',
                        $request->file('attachments'),
                        $request->file('attachments')->getClientOriginalName()
                    );
                }

                // Realizar la solicitud HTTP POST
                $response = $response->post($apiUrl, [
                    'report_id' => $generatedReportId,
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'state' => $municipioPol->EstadoPolId,
                    'municipality' => $municipioPol->MunicipioPolId,
                    'report_type' => $validated['report_type'],
                    'address' => $validated['address'],
                    'comment' => $validated['comment'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'gps_location' => $validated['gps_location'],
                ]);

                // Verificar si la solicitud fue exitosa
                if ($response->successful()) {
                    return response()->json([
                        'message' => 'Report successfully submitted',
                        'data' => $response->json(),
                    ]);
                } else {
                    throw new \Exception('Failed to submit the report. Check the service URL or configuration.', 500);
                }
            });

            return response()->json(['message' => 'Report successfully registered'], 201);
        } catch (\Exception $e) {
            Log::error('Error saving report: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error while saving the report',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveSimpleReport(Request $request)
    {
        try {
            Log::info('Datos del request:', $request->all());

            // Validación de los datos, incluyendo el report_id
            $validated = $request->validate([
                'report_id' => 'required|uuid',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'report_type' => 'required|string|max:50',
                'comment' => 'nullable|string|max:90',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'gps_location' => 'nullable|string|max:50',
                'reported_photo' => 'nullable|file|image|max:2048',
                'end_photo' => 'nullable|file|image|max:2048',
                'attachments' => 'nullable|file|max:2048',
                'mobile_model' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'app_version' => 'nullable|string|max:50',
                'is_offline' => 'nullable|boolean',
                'network_type' => 'nullable|string|max:20',
                'imei' => 'nullable|string|max:20',
            ]);

            // Obtener la dirección usando las coordenadas si no se ha proporcionado
            if (empty($validated['address'])) {
                $validated['address'] = LocationHelper::obtenerDireccionCompleta($validated['latitude'], $validated['longitude']);
            }

            // Transacción para asegurar la concurrencia
            DB::transaction(function () use ($validated, $request) {

                // Consulta del polígono para obtener el estado y el municipio
                $municipioPol = DB::table('MunicipioPol')
                    ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                    ->first();

                if (!$municipioPol) {
                    throw new \Exception('No se encontró un municipio para las coordenadas proporcionadas', 404);
                }

                // Generar el folio único
                $lastFolio = DB::table('reports')->max('report_folio');
                $newFolio = $lastFolio ? $lastFolio + 1 : 100000;

                // Guardar imágenes usando ImageHelper
                $reportedPhotoPath = $request->hasFile('reported_photo')
                    ? ImageHelper::storeImage($request->file('reported_photo'), 'reports')
                    : null;

                $endPhotoPath = $request->hasFile('end_photo')
                    ? ImageHelper::storeImage($request->file('end_photo'), 'reports')
                    : null;

                $attachmentData = $request->hasFile('attachments')
                    ? ImageHelper::storeFile($request->file('attachments'), 'reports')
                    : null;

                // Registrar el reporte en la tabla `reports` del proyecto 1
                DB::table('reports')->insert([
                    'report_id' => $validated['report_id'],
                    'report_folio' => $newFolio,
                    'report_registration_date' => now()->format('Y-m-d'),
                    'report_registration_time' => now()->format('Y-m-d H:i:s'),
                    'state_id' => $municipioPol->EstadoPolId,
                    'municipality_id' => $municipioPol->MunicipioPolId,
                    'report_type_id' => $validated['report_type'],
                    'report_status_id' => 'Reportado',
                    'report_address' => $validated['address'],
                    'custom_report' => $validated['report_type'],
                    'report_comment' => $validated['comment'],
                    'gps_location' => $validated['gps_location'],
                    'geospatial_location' => DB::raw("ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326)"),
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'is_contracted_municipality' => 1, // No validamos municipios contratados aquí
                    'mobile_model' => $validated['mobile_model'],
                    'os_version' => $validated['os_version'],
                    'app_version' => $validated['app_version'],
                    'is_offline' => $validated['is_offline'],
                    'network_type' => $validated['network_type'],
                    'imei' => $validated['imei'],
                    'reported_photo' => $reportedPhotoPath,
                    'end_photo' => $endPhotoPath,
                    'attachments' => $attachmentData ? json_encode($attachmentData) : null,
                    'generated_from' => 'Móvil',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return response()->json(['message' => 'Reporte registrado exitosamente'], 201);
        } catch (\Exception $e) {
            Log::error('Error al guardar el reporte: ' . $e->getMessage());

            return response()->json([
                'message' => 'Hubo un error al guardar el reporte',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Consultar los detalles de un reporte.
     *
     * @param Request $request Solicitud HTTP con el ID del reporte.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los detalles del reporte o un mensaje de error.
     * 
     * Funcionalidad:
     * 1. Valida el ID del reporte.
     * 2. Consulta el reporte desde la base de datos utilizando joins para obtener detalles del estado y municipio.
     * 3. Registra la consulta en los logs.
     * 4. Devuelve el reporte o un mensaje de error si no se encuentra.
     */
    public function reportConsulta(Request $request)
    {
        // Obtener el ID del reporte del request y registrar en los logs
        $reportId = $request->input('report_id');
        Log::info('Iniciando consulta de reporte', ['report_id' => $reportId]);

        try {
            // Consultar el reporte desde la base de datos usando joins
            $report = DB::table('reports')
                ->leftJoin('estado', 'reports.state_id', '=', 'estado.EstadoId')
                ->leftJoin('municipio', function ($join) {
                    $join->on('reports.state_id', '=', 'municipio.EstadoId')
                        ->on('reports.municipality_id', '=', 'municipio.MunicipioId');
                })
                ->select(
                    'reports.report_id',
                    'reports.report_folio',
                    'reports.report_registration_date',
                    'reports.report_registration_time',
                    'reports.report_reported_date',
                    'reports.report_reported_time',
                    'reports.state_id',
                    'reports.municipality_id',
                    'reports.report_type_id',
                    'reports.report_status_id',
                    'reports.is_contracted_municipality',
                    'reports.report_address',
                    'reports.custom_report',
                    'reports.report_comment',
                    'reports.gps_location',
                    'reports.end_date',
                    'reports.end_time',
                    'reports.end_comment',
                    'reports.end_photo',
                    'reports.device_id',
                    'reports.attention_user_id',
                    'reports.attention_user_nick',
                    'reports.blocked_report',
                    'reports.phone',
                    'reports.email',
                    'reports.reported_photo',
                    'reports.attachments',
                    'reports.response_text',
                    'reports.created_at',
                    'reports.updated_at',
                    'estado.EstadoNombre',
                    'municipio.MunicipioNombre'
                )
                ->where('reports.report_id', $reportId)
                ->first();

            // Verificar si el reporte fue encontrado
            if (!$report) {
                Log::warning('Reporte no encontrado', ['report_id' => $reportId]);
                return response()->json(['error' => 'Reporte no encontrado'], 404);
            }

            // Registrar la consulta exitosa
            Log::info('Reporte encontrado', ['report_id' => $reportId, 'estado' => $report->EstadoNombre, 'municipio' => $report->MunicipioNombre]);

            // Retornar el reporte en formato JSON
            return response()->json($report);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al consultar el reporte', ['report_id' => $reportId, 'error' => $e->getMessage()]);

            // Retornar una respuesta de error
            return response()->json(['error' => 'Ocurrió un error al consultar el reporte'], 500);
        }
    }

    /**
     * Obtener los tipos de reporte activos, ordenados por prioridad.
     *
     * @param Request $request Solicitud HTTP (no se requiere parámetros específicos).
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los tipos de reporte activos.
     * 
     * Funcionalidad:
     * 1. Consulta los tipos de reporte activos.
     * 2. Ordena los tipos de reporte por prioridad.
     * 3. Devuelve los tipos de reporte en formato JSON con el formato 'value' y 'label'.
     */
    public function getTipoReporte(Request $request)
    {
        // Obtener solo los tipos de reporte activos, ordenados por prioridad
        $reportTypes = ReportType::where('ReportTypeIsActive', true) // Solo tipos activos
            ->orderBy('ReportTypeOrderPriority', 'asc') // Orden por prioridad
            ->get() // Obtener todos los campos
            ->map(function ($reportType) {
                return [
                    'value' => $reportType->ReportTypeName,
                    'label' => $reportType->ReportTypeName,
                ];
            });

        // Retornar los resultados en formato JSON
        return response()->json($reportTypes);
    }

    public function getTipoReporteMunicipio(Request $request)
    {
        try {
            // Validar latitud y longitud desde la solicitud
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // Consulta del polígono para determinar el municipio
            $municipioPol = DB::table('MunicipioPol')
                ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                ->first();

            // Log para ver los detalles del municipioPol
            Log::info('MunicipioPol encontrado', ['municipioPol' => $municipioPol]);

            // Verificar si se encontró el municipio correspondiente
            if (!$municipioPol) {
                return response()->json(['error' => 'No municipality found for the provided coordinates'], 404);
            }

            // Buscar en la tabla de municipios contratados
            $contractedMunicipality = DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first();

            // Log para ver los detalles del municipio contratado
            Log::info('Municipio contratado encontrado', ['contractedMunicipality' => $contractedMunicipality]);

            // Verificar si es un municipio contratado
            if (!$contractedMunicipality) {
                return response()->json(['error' => 'Municipality is not contracted'], 404);
            }

            // Preparar los headers con el token para hacer la solicitud al API del municipio
            $headers = [
                'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                'Content-Type' => 'application/json',
            ];

            // Buscar el servicio 'Listar tipo de reporte por municipio'
            $municipalityService = DB::table('municipality_services')
                ->where('municipality_id', $contractedMunicipality->id)
                ->where('service_name', 'Listar tipo de reporte por municipio')
                ->first();

            // Verificar si el servicio existe
            if (!$municipalityService) {
                return response()->json(['error' => 'No service found to list report types for the municipality'], 404);
            }

            // Concatenar la URL base del municipio con la API URL del servicio
            $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

            // Realizar la solicitud HTTP GET a la URL del servicio
            $response = Http::withHeaders($headers)->get($apiUrl);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                return response()->json([
                    'message' => 'Report types successfully retrieved',
                    'data' => $response->json(),
                ]);
            } else {
                return response()->json(['error' => 'Failed to retrieve report types. Check the service URL or configuration.'], 500);
            }
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs
            Log::error('Error getting report types by municipality: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error while getting the report types',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEstatusReporte(Request $request)
    {
        try {
            // Validar latitud y longitud desde la solicitud
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // Consulta del polígono para determinar el municipio
            $municipioPol = DB::table('MunicipioPol')
                ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                ->first();

            // Log para ver los detalles del municipioPol
            Log::info('MunicipioPol encontrado', ['municipioPol' => $municipioPol]);

            // Verificar si se encontró el municipio correspondiente
            if (!$municipioPol) {
                return response()->json(['error' => 'No municipality found for the provided coordinates'], 404);
            }

            // Buscar en la tabla de municipios contratados
            $contractedMunicipality = DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first();

            // Log para ver los detalles del municipio contratado
            Log::info('Municipio contratado encontrado', ['contractedMunicipality' => $contractedMunicipality]);

            // Verificar si es un municipio contratado
            if (!$contractedMunicipality) {
                return response()->json(['error' => 'Municipality is not contracted'], 404);
            }

            // Preparar los headers con el token para hacer la solicitud al API del municipio
            $headers = [
                'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                'Content-Type' => 'application/json',
            ];

            // Buscar el servicio 'Listar estatus de reporte por municipio'
            $municipalityService = DB::table('municipality_services')
                ->where('municipality_id', $contractedMunicipality->id)
                ->where('service_name', 'Listar estatus de reporte por municipio')
                ->first();

            // Verificar si el servicio existe
            if (!$municipalityService) {
                return response()->json(['error' => 'No service found to list report status for the municipality'], 404);
            }

            // Concatenar la URL base del municipio con la API URL del servicio
            $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

            // Realizar la solicitud HTTP GET a la URL del servicio
            $response = Http::withHeaders($headers)->get($apiUrl);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                return response()->json([
                    'message' => 'Report status successfully retrieved',
                    'data' => $response->json(),
                ]);
            } else {
                return response()->json(['error' => 'Failed to retrieve report status. Check the service URL or configuration.'], 500);
            }
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs
            Log::error('Error getting report status by municipality: ' . $e->getMessage());

            return response()->json([
                'message' => 'There was an error while getting the report status',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateReport(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'report_id' => 'required|uuid',
                'report_status' => 'required|string|max:50',
                'report_comment' => 'nullable|string|max:90',
                'report_address' => 'nullable|string|max:1024',
            ]);

            // Buscar el reporte en la base de datos
            $report = DB::table('reports')->where('report_id', $validated['report_id'])->first();

            if (!$report) {
                return response()->json(['error' => 'No se encontró el reporte con el ID proporcionado'], 404);
            }

            // Actualizar el reporte con los nuevos valores
            DB::table('reports')
                ->where('report_id', $validated['report_id'])
                ->update([
                    'report_status_id' => $validated['report_status'],
                    'report_comment' => $validated['report_comment'],
                    'report_address' => $validated['report_address'],
                    'updated_at' => now(),
                ]);

            return response()->json(['message' => 'Reporte actualizado exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el reporte: ' . $e->getMessage());

            return response()->json([
                'message' => 'Hubo un error al actualizar el reporte',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function registerUser(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:mobile_users',
                'password' => 'required|string|min:6',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:100',
                'device_id' => 'nullable|string|max:128',
                'mobile_model' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'app_version' => 'nullable|string|max:50',
                'network_type' => 'nullable|string|max:20',
                'imei' => 'nullable|string|max:20',
                'status' => 'nullable|string|max:50|in:active,inactive',
            ]);

            // Encriptar la contraseña y añadir las marcas de tiempo
            $validated['password'] = bcrypt($validated['password']);
            $validated['created_at'] = now();
            $validated['updated_at'] = now();

            // Creación del usuario
            $user = DB::table('mobile_users')->insert($validated);

            return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al registrar el usuario',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            // Validación de las credenciales
            $validated = $request->validate([
                'email' => 'required|string|email|max:100',
                'password' => 'required|string',
            ]);

            // Buscar el usuario por el correo
            $user = DB::table('mobile_users')->where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            // Generar un token para la sesión
            $token = Str::random(60);
            DB::table('mobile_users')
                ->where('email', $validated['email'])
                ->update(['remember_token' => $token]);

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'token' => $token,
                'user' => [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'occupation' => $user->occupation,
                    'status' => $user->status,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error en inicio de sesión: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error en el inicio de sesión',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validated = $request->validate([
                'user_id' => 'required|exists:mobile_users,user_id', // ID del usuario para buscarlo en la base de datos
                'name' => 'nullable|string|max:100',
                'email' => 'nullable|string|email|max:100|unique:mobile_users,email,' . $request->user_id . ',user_id',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:100',
                'device_id' => 'nullable|string|max:128',
                'mobile_model' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'app_version' => 'nullable|string|max:50',
                'network_type' => 'nullable|string|max:20',
                'imei' => 'nullable|string|max:20',
                'status' => 'nullable|string|max:50|in:active,inactive',
            ]);

            // Actualización del usuario con los nuevos datos
            $updateData = array_filter($validated); // Filtra valores nulos para evitar sobrescribir con null
            $updateData['updated_at'] = now();

            DB::table('mobile_users')
                ->where('user_id', $validated['user_id'])
                ->update($updateData);

            return response()->json(['message' => 'Usuario actualizado exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al actualizar el usuario',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            // Validación de la entrada
            $validated = $request->validate([
                'user_id' => 'required|exists:mobile_users,user_id', // ID del usuario para identificarlo
                'current_password' => 'required|string', // Contraseña actual
                'new_password' => 'required|string|min:6|different:current_password', // Nueva contraseña
            ]);

            // Buscar al usuario
            $user = DB::table('mobile_users')->where('user_id', $validated['user_id'])->first();

            // Verificar la contraseña actual
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json(['error' => 'La contraseña actual no es correcta'], 401);
            }

            // Actualizar la contraseña con la nueva contraseña encriptada
            DB::table('mobile_users')
                ->where('user_id', $validated['user_id'])
                ->update([
                    'password' => bcrypt($validated['new_password']),
                    'updated_at' => now(),
                ]);

            return response()->json(['message' => 'Contraseña actualizada exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la contraseña: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al actualizar la contraseña',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
