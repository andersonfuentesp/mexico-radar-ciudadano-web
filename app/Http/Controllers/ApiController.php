<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Helpers\LocationHelper;
use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\MunicipioPol;
use App\Models\ReportStatus;
use App\Models\ReportType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetSuccessMail;
use Illuminate\Support\Facades\Mail;

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
        // Verificar si el estado existe
        $estado = DB::table('estado')->where('EstadoId', $estadoId)->first();
        if (!$estado) {
            return response()->json(['error' => 'Estado no encontrado.'], 404);
        }

        // Obtener municipios del estado especificado que no tengan ningún contrato privado
        $municipios = DB::table('municipio')
            ->where('EstadoId', $estadoId)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('contracted_municipalities')
                    ->whereColumn('contracted_municipalities.municipality_id', 'municipio.MunicipioId')
                    ->where('contracted_municipalities.is_private', true);
            })
            ->orderBy('MunicipioNombre', 'asc')
            ->get();

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
        $municipioPol = DB::table('municipiopol')
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
        $municipioPol = DB::table('municipiopol')
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
     * Obtener los directorios del municipio.
     *
     * @param Request $request Contiene los parámetros 'estado_id', 'municipio_id' y el filtro opcional 'search_dependencia'.
     * @return \Illuminate\Http\JsonResponse Respuesta en JSON con los directorios o un mensaje de error.
     * @throws \Illuminate\Validation\ValidationException Si los parámetros son inválidos.
     */
    public function getDirectorios(Request $request)
    {
        // Validar que se hayan enviado los parámetros obligatorios
        $request->validate([
            'estado_id' => 'required|integer|exists:estado,EstadoId',
            'municipio_id' => 'required|integer|exists:municipio,MunicipioId',
        ]);

        // 1. Buscar el polígono del municipio usando el estado y municipio proporcionados
        $municipioPol = DB::table('municipiopol')
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

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Listar directorios'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Listar directorios')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para listar directorios en el municipio'], 404);
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

        // Agregar el filtro opcional de dependencia si está presente
        if ($request->has('search_dependencia') && !empty($request->search_dependencia)) {
            $params['search_dependencia'] = $request->search_dependencia;
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
                return response()->json(['data' => [], 'message' => 'No se encontraron directorios disponibles en este momento.'], 200);
            }
        } catch (\Exception $e) {
            // En caso de error de conexión u otra excepción
            Log::error("Error al intentar conectar con la API en getDirectorios: " . $e->getMessage());
            return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de directorios.'], 200);
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
        $municipioPol = DB::table('municipiopol')
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
        $municipioPol = DB::table('municipiopol')
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

    public function getEmergencyNumbersByLocation(Request $request)
    {
        try {
            // Validar latitud y longitud
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // Buscar el municipio en la tabla MunicipioPol usando las coordenadas
            $municipioPol = DB::table('municipiopol')
                ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                ->first();

            if (!$municipioPol) {
                return response()->json(['error' => 'No se encontró un municipio para las coordenadas proporcionadas'], 404);
            }

            // Buscar en la tabla de municipios contratados
            $contractedMunicipality = DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first();

            if (!$contractedMunicipality) {
                return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
            }

            // Buscar el servicio "Listar numeros de emergencia"
            $municipalityService = DB::table('municipality_services')
                ->where('municipality_id', $contractedMunicipality->id)
                ->where('service_name', 'Listar numeros de emergencia')
                ->first();

            if (!$municipalityService) {
                return response()->json(['error' => 'No se encontró un servicio para listar números de emergencia en el municipio'], 404);
            }

            // Construir la URL de la API con parámetros de consulta
            $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');
            $queryParams = [
                'estado_id' => $municipioPol->EstadoPolId,
                'municipio_id' => $municipioPol->MunicipioPolId,
            ];

            // Preparar los headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                'Content-Type' => 'application/json',
            ];

            // Realizar la solicitud HTTP con los parámetros de consulta
            try {
                $response = Http::withHeaders($headers)->get($apiUrl, $queryParams);

                if ($response->successful()) {
                    return response()->json($response->json());
                } else {
                    return response()->json(['data' => [], 'message' => 'No se encontraron números de emergencia disponibles en este momento.'], 200);
                }
            } catch (\Exception $e) {
                Log::error("Error al intentar conectar con la API en getEmergencyNumbersByLocation: " . $e->getMessage());
                return response()->json(['data' => [], 'message' => 'No se pudo establecer conexión con el servicio de números de emergencia.'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Error getting emergency numbers by location: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud.'], 500);
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
        $municipioPol = DB::table('municipiopol')
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
            Log::info('Inicio de saveReport', $request->all());

            // Validación de los datos
            Log::info('Validando los datos del request...');
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'report_type' => 'required|string|max:50',
                'comment' => 'nullable|string|max:90',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|string|email|max:100',
                'gps_location' => 'nullable|string|max:50',
                'reported_photo' => 'nullable|file|image|max:20480',
                'end_photo' => 'nullable|file|image|max:20480',
                'attachments' => 'nullable|file|max:8192',
                'mobile_model' => 'nullable|string|max:100',
                'os_version' => 'nullable|string|max:50',
                'app_version' => 'nullable|string|max:50',
                'is_offline' => 'nullable|boolean',
                'network_type' => 'nullable|string|max:20',
                'imei' => 'nullable|string|max:20',
                // address no está en las validaciones, pero lo tratamos más abajo
            ]);
            Log::info('Datos validados:', $validated);

            // Obtener la dirección usando las coordenadas si no se ha proporcionado
            if (empty($validated['address'])) {
                Log::info('Generando dirección a partir de coordenadas...');
                $validated['address'] = LocationHelper::obtenerDireccionCompleta($validated['latitude'], $validated['longitude']);
                Log::info('Dirección generada:', ['address' => $validated['address']]);
            }

            // Consultar MunicipioPol fuera de la transacción
            Log::info('Consultando MunicipioPol usando MunicipioPolGeoPolygon...');
            $municipioPol = DB::table('municipiopol')
                ->select(
                    'EstadoPolId',
                    'MunicipioPolId',
                    'MunicipioPolNumero',
                    'MunicipioPolPoligono',
                    'MunicipioPolChar',
                    'MunicipioPolKml',
                    'MunicipioPolGeoPolygon'
                )
                ->whereRaw("ST_Contains(MunicipioPolGeoPolygon, ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                ->first();

            if (!$municipioPol) {
                Log::warning('No se encontró municipio para las coordenadas proporcionadas');
                return response()->json(['message' => 'No municipality found for the provided coordinates'], 200);
            }

            // Buscar en la tabla contracted_municipalities fuera de la transacción
            Log::info('Consultando contracted_municipalities...');
            $contractedMunicipality = DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first();
            Log::info('Municipio contratado:', ['contracted' => $contractedMunicipality]);

            // Validar si el municipio es privado
            if ($contractedMunicipality && $contractedMunicipality->is_private) {
                Log::info('Validando acceso para municipios privados...');

                // Verificar que el email esté en mobile_users
                $mobileUser = DB::table('mobile_users')
                    ->where('email', $validated['email'])
                    ->first();

                if (!$mobileUser) {
                    Log::error('El email no pertenece a un usuario registrado.');
                    return response()->json([
                        'message' => 'Access denied: The email is not associated with a registered user.',
                        'status' => 'error'
                    ], 403);
                }

                // Verificar que el usuario tenga una ubicación activa en mobile_user_locations
                $userLocation = DB::table('mobile_user_locations')
                    ->where('user_id', $mobileUser->user_id)
                    ->where('state_id', $municipioPol->EstadoPolId)
                    ->where('municipality_id', $municipioPol->MunicipioPolId)
                    ->where('is_active', 1)
                    ->first();

                if (!$userLocation) {
                    Log::error('El usuario no tiene acceso activo a esta ubicación.');
                    return response()->json([
                        'message' => 'Access denied: The user does not have active access to this location.',
                        'status' => 'error'
                    ], 403);
                }
                // 2) Nuevo bloque: Si el municipio NO está contratado o está contratado pero NO es privado
            } else {
                Log::info('Validando acceso para municipios NO contratados o contratados sin restricción privada...');

                // 1) Verificar si el usuario está registrado en mobile_users
                $mobileUser = DB::table('mobile_users')
                    ->where('email', $validated['email'])
                    ->first();

                if ($mobileUser) {
                    // 2) Obtenemos TODAS las ubicaciones activas del usuario
                    $allUserLocations = DB::table('mobile_user_locations')
                        ->where('user_id', $mobileUser->user_id)
                        ->where('is_active', 1)
                        ->get();

                    if ($allUserLocations->count() === 0) {
                        // NO tiene ubicaciones en mobile_user_locations
                        Log::info('El usuario no tiene ubicaciones activas, pero no es municipio privado; se permite continuar.');
                    } else {
                        // 3) Hay al menos un registro. Verificamos si alguno coincide con el municipio/estado recibidos
                        $matchingLocation = $allUserLocations->first(function ($location) use ($municipioPol) {
                            return $location->state_id == $municipioPol->EstadoPolId
                                && $location->municipality_id == $municipioPol->MunicipioPolId;
                        });

                        if ($matchingLocation) {
                            // Coincide, se puede continuar
                            Log::info('El usuario tiene al menos una ubicación activa que coincide con este municipio (no privado).');
                        } else {
                            // Tiene ubicaciones, pero ninguna coincide con este municipio
                            Log::error('El usuario tiene ubicaciones activas, pero no para este municipio.');
                            return response()->json([
                                'message' => 'Access denied: The user does not have active access to this location.',
                                'status'  => 'error'
                            ], 403);
                        }
                    }
                } else {
                    // El email no existe en mobile_users; como no es privado, no exigimos nada
                    Log::info('El email no pertenece a un usuario registrado, pero no es municipio privado, por lo que se permite continuar.');
                }

                // OJO: De aquí en adelante, se continúa con el flujo normal...
            }

            // Continuar con el resto del proceso para guardar el reporte
            Log::info('Acceso validado. Continuando con el registro del reporte...');

            // Generar el report_id
            $generatedReportId = Str::uuid();
            Log::info('ID de reporte generado:', ['report_id' => $generatedReportId]);

            // Declaramos $newFolio antes de la transacción
            $newFolio = null;

            // Transacción para la inserción en la base de datos
            DB::transaction(function () use ($validated, $request, $generatedReportId, $municipioPol, $contractedMunicipality, &$newFolio) {
                Log::info('Iniciando transacción para el guardado del reporte...');

                // Si el municipio es contratado
                if ($contractedMunicipality) {
                    // Obtener el último folio para ese municipio (filtro solo por municipality_id)
                    $lastFolio = DB::table('reports')
                        ->where('municipality_id', $municipioPol->MunicipioPolId)
                        ->max('report_folio');
                } else {
                    // Si no es contratado, filtrar por BOTH municipality_id y is_contracted_municipality = 0
                    $lastFolio = DB::table('reports')
                        ->where('municipality_id', $municipioPol->MunicipioPolId)
                        ->where('is_contracted_municipality', 0)
                        ->max('report_folio');
                }

                // Calcular el folio
                $newFolio = $lastFolio ? $lastFolio + 1 : 1;
                Log::info('Folio de reporte calculado:', ['folio' => $newFolio]);

                // Guardar imágenes si están presentes
                $reportedPhotoPath = $request->hasFile('reported_photo')
                    ? ImageHelper::storeImage($request->file('reported_photo'), 'reports')
                    : null;
                $endPhotoPath = $request->hasFile('end_photo')
                    ? ImageHelper::storeImage($request->file('end_photo'), 'reports')
                    : null;
                $attachmentData = $request->hasFile('attachments')
                    ? ImageHelper::storeFile($request->file('attachments'), 'reports')
                    : null;
                Log::info('Paths de imágenes y archivos adjuntos', [
                    'reported_photo' => $reportedPhotoPath,
                    'end_photo' => $endPhotoPath,
                    'attachments' => $attachmentData,
                ]);

                // Insertar reporte en la base de datos
                Log::info('Insertando reporte en la base de datos...');
                DB::table('reports')->insert([
                    'report_id' => $generatedReportId,
                    'report_folio' => $newFolio,
                    'report_registration_date' => now()->format('Y-m-d'),
                    'report_registration_time' => now()->format('Y-m-d H:i:s'),
                    'state_id' => $municipioPol->EstadoPolId,
                    'municipality_id' => $municipioPol->MunicipioPolId,
                    'report_type_id' => $validated['report_type'],
                    'report_status_id' => 'Reportado',
                    'report_address' => $validated['address'],
                    'custom_report' => $validated['report_type'],
                    // Forzamos comment a "-" si viene vacío o null
                    'report_comment' => empty($validated['comment']) ? '-' : $validated['comment'],
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

                Log::info('Reporte insertado exitosamente en la tabla reports');
            });

            // Lógica de envío del reporte al API externo fuera de la transacción
            if ($contractedMunicipality) {
                Log::info('Consultando municipality_services para el servicio Ingresar reporte...');
                $municipalityService = DB::table('municipality_services')
                    ->where('municipality_id', $contractedMunicipality->id)
                    ->where('service_name', 'Ingresar reporte')
                    ->first();

                if (!$municipalityService) {
                    Log::warning('No se encontró el servicio para ingresar el reporte en el municipio contratado');
                    return response()->json([
                        'message' => 'No service found to submit the report in the municipality',
                        'status' => 'error'
                    ], 200);
                }

                Log::info('Preparando la solicitud HTTP POST con archivos adjuntos y datos...');

                // Mostrar la URL de la API y los encabezados utilizados
                $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');
                Log::info('API URL construida:', ['apiUrl' => $apiUrl]);

                $headers = [
                    'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                ];
                Log::info('Encabezados de la solicitud:', $headers);

                // Construimos los datos asegurando que comment y address no sean null
                $data = [
                    'report_id'   => $generatedReportId,
                    'latitude'    => $validated['latitude'],
                    'longitude'   => $validated['longitude'],
                    'state'       => $municipioPol->EstadoPolId,
                    'municipality' => $municipioPol->MunicipioPolId,
                    'report_type' => $validated['report_type'],
                    // Forzamos address a "-" si viene vacío o null
                    'address'     => empty($validated['address']) ? '-' : $validated['address'],
                    // Forzamos comment a "-" si viene vacío o null
                    'comment'     => empty($validated['comment']) ? '-' : $validated['comment'],
                    'phone'       => $validated['phone'],
                    'email'       => $validated['email'],
                    'gps_location' => $validated['gps_location'],
                    'folio'       => $newFolio
                ];
                Log::info('Datos enviados en la solicitud:', $data);

                // Iniciar la solicitud con los datos básicos y los encabezados
                $response = Http::asMultipart()->withHeaders($headers);

                // Adjuntamos archivos si existen
                if ($request->hasFile('reported_photo')) {
                    $response = $response->attach(
                        'reported_photo',
                        file_get_contents($request->file('reported_photo')->getRealPath()),
                        $request->file('reported_photo')->getClientOriginalName()
                    );
                }

                if ($request->hasFile('end_photo')) {
                    $response = $response->attach(
                        'end_photo',
                        file_get_contents($request->file('end_photo')->getRealPath()),
                        $request->file('end_photo')->getClientOriginalName()
                    );
                }

                if ($request->hasFile('attachments')) {
                    $response = $response->attach(
                        'attachments',
                        file_get_contents($request->file('attachments')->getRealPath()),
                        $request->file('attachments')->getClientOriginalName()
                    );
                }

                // Ejecutar la solicitud HTTP POST y capturar la respuesta
                $response = $response->post($apiUrl, $data);

                if ($response->successful()) {
                    Log::info('Solicitud HTTP POST exitosa. Respuesta recibida:', $response->json());
                    return response()->json([
                        'message' => 'Report successfully submitted',
                        'data'    => $response->json(),
                    ], 200);
                } else {
                    // Log adicional para el error con detalles de la respuesta recibida
                    Log::error('Error en la solicitud HTTP POST. Respuesta no exitosa:', [
                        'status' => $response->status(),
                        'body'   => $response->body()
                    ]);
                    return response()->json([
                        'message' => 'Failed to submit the report. Check the service URL or configuration.',
                        'status'  => 'error'
                    ], 200);
                }
            }

            Log::info('Reporte registrado exitosamente');
            return response()->json(['message' => 'Report successfully registered'], 201);
        } catch (\Exception $e) {
            Log::error('Error en saveReport: ' . $e->getMessage());
            return response()->json([
                'message' => 'There was an error while saving the report',
                'status'  => 'error',
                'error'   => $e->getMessage()
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
                $municipioPol = DB::table('municipiopol')
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
                    'generated_from' => 'Web',
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
            $municipioPol = DB::table('municipiopol')
                ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT({$validated['longitude']} {$validated['latitude']})', 4326))")
                ->first();

            // Log para ver los detalles del municipioPol
            if (!$municipioPol) {
                Log::info('No municipality found for the provided coordinates', ['latitude' => $validated['latitude'], 'longitude' => $validated['longitude']]);
            }

            // Buscar en la tabla de municipios contratados
            $contractedMunicipality = $municipioPol ? DB::table('contracted_municipalities')
                ->where('state_id', $municipioPol->EstadoPolId)
                ->where('municipality_id', $municipioPol->MunicipioPolId)
                ->first() : null;

            // Log para ver los detalles del municipio contratado
            if ($municipioPol && !$contractedMunicipality) {
                Log::info('Municipality is not contracted', ['EstadoPolId' => $municipioPol->EstadoPolId, 'MunicipioPolId' => $municipioPol->MunicipioPolId]);
            }

            // Buscar el servicio 'Listar tipo de reporte por municipio'
            $municipalityService = $contractedMunicipality ? DB::table('municipality_services')
                ->where('municipality_id', $contractedMunicipality->id)
                ->where('service_name', 'Listar tipo de reporte por municipio')
                ->first() : null;

            if ($contractedMunicipality && !$municipalityService) {
                Log::info('No service found to list report types for the municipality', ['municipality_id' => $contractedMunicipality->id]);
            }

            // Si se tiene el servicio, realizar la solicitud HTTP
            if ($municipalityService) {
                $headers = [
                    'Authorization' => 'Bearer ' . $contractedMunicipality->token,
                    'Content-Type' => 'application/json',
                ];

                $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');
                $response = Http::withHeaders($headers)->get($apiUrl);

                if ($response->successful()) {
                    return response()->json([
                        'message' => 'Report types successfully retrieved',
                        'data' => $response->json(),
                    ]);
                }
            }

            // Retornar tipos de reporte locales en caso de que falle la solicitud externa o no se cumplan las condiciones anteriores
            $reportTypes = ReportType::where('ReportTypeIsActive', true)
                ->orderBy('ReportTypeOrderPriority', 'asc')
                ->paginate(100);

            return response()->json([
                'message' => 'Report types retrieved from local data due to missing or failed external conditions',
                'data' => $reportTypes,
            ], 200);
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs
            Log::error('Error getting report types by municipality: ' . $e->getMessage());

            // Retornar los tipos de reporte locales en caso de error
            $reportTypes = ReportType::where('ReportTypeIsActive', true)
                ->orderBy('ReportTypeOrderPriority', 'asc')
                ->paginate(100);

            return response()->json([
                'message' => 'Report types retrieved from local data due to an error',
                'data' => $reportTypes,
            ], 200);
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
            $municipioPol = DB::table('municipiopol')
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
                ->where('service_name', 'Listar estatus de reporte')
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
                // Si falla la solicitud al API del municipio, listar los estados de reporte locales
                $reportStatuses = ReportStatus::orderBy('report_status_name', 'asc')->paginate(100);

                return response()->json([
                    'message' => 'Report status retrieved from local data due to failed external request',
                    'data' => $reportStatuses,
                ], 200);
            }
        } catch (\Exception $e) {
            // Registrar cualquier error en los logs
            Log::error('Error getting report status by municipality: ' . $e->getMessage());

            // En caso de error, retornar los estados de reporte locales
            $reportStatuses = ReportStatus::orderBy('report_status_name', 'asc')->get();

            return response()->json([
                'message' => 'Report status retrieved from local data due to an error',
                'data' => $reportStatuses,
            ], 200);
        }
    }

    public function reportUser(Request $request)
    {
        // Validar que el 'user_email' esté presente en la solicitud
        $request->validate([
            'user_email' => 'required|email'
        ]);

        // Obtener el 'user_email' de la solicitud
        $userEmail = $request->input('user_email');

        // Consultar los reportes que coincidan con el 'user_email', omitiendo el campo 'geospatial_location'
        $reports = DB::table('reports')
            ->where('email', $userEmail)
            ->select(
                'report_id',
                'report_folio',
                'report_registration_date',
                'report_registration_time',
                'report_reported_date',
                'report_reported_time',
                'state_id',
                'municipality_id',
                'report_type_id',
                'report_status_id',
                'report_address',
                'custom_report',
                'report_comment',
                'gps_location',
                'end_date',
                'end_time',
                'end_comment',
                'end_photo',
                'end_photo_gxi',
                'device_id',
                'attention_user_id',
                'attention_user_nick',
                'attention_user_id_gam',
                'encrypted_attention_user',
                'blocked_report',
                'block_date',
                'block_user',
                'email',
                'phone',
                'reported_photo',
                'reported_photo_gxi',
                'status_sd',
                'status_list_sd',
                'twitter_status',
                'response_text',
                'is_contracted_municipality',
                'mobile_model',
                'os_version',
                'app_version',
                'is_offline',
                'attachments',
                'network_type',
                'imei',
                'generated_from',
                'created_at',
                'updated_at'
            )
            ->orderBy('created_at', 'desc') // Agregar orden descendente por la fecha de creación
            ->get();

        // Retornar la respuesta con los reportes filtrados
        return response()->json([
            'success' => true,
            'data' => $reports
        ], 200);
    }

    public function searchUserReports(Request $request)
    {
        // Validación de los parámetros
        $request->validate([
            'user_email' => 'required|email',
            'report_type_id' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        // Obtener los parámetros de la solicitud
        $userEmail = $request->input('user_email');
        $reportTypeId = $request->input('report_type_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Construir la consulta inicial
        $query = DB::table('reports')
            ->where('email', $userEmail);

        // Filtrar por tipo de reporte, si se proporciona
        if (!empty($reportTypeId)) {
            $query->where('report_type_id', $reportTypeId);
        }

        // Filtrar por rango de fechas en 'report_registration_time'
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('report_registration_time', [$startDate, $endDate]);
        }

        // Seleccionar los campos requeridos
        $reports = $query->select(
            'report_id',
            'report_folio',
            'report_registration_date',
            'report_registration_time',
            'report_reported_date',
            'report_reported_time',
            'state_id',
            'municipality_id',
            'report_type_id',
            'report_status_id',
            'report_address',
            'custom_report',
            'report_comment',
            'gps_location',
            'end_date',
            'end_time',
            'end_comment',
            'end_photo',
            'end_photo_gxi',
            'device_id',
            'attention_user_id',
            'attention_user_nick',
            'attention_user_id_gam',
            'encrypted_attention_user',
            'blocked_report',
            'block_date',
            'block_user',
            'email',
            'phone',
            'reported_photo',
            'reported_photo_gxi',
            'status_sd',
            'status_list_sd',
            'twitter_status',
            'response_text',
            'is_contracted_municipality',
            'mobile_model',
            'os_version',
            'app_version',
            'is_offline',
            'attachments',
            'network_type',
            'imei',
            'generated_from',
            'created_at',
            'updated_at'
        )->get();

        // Retornar la respuesta con los reportes filtrados
        return response()->json([
            'success' => true,
            'data' => $reports
        ], 200);
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
            ], [
                'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $validated['created_at'] = now();
            $validated['updated_at'] = now();

            DB::table('mobile_users')->insert($validated);

            return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['email'])) {
                return response()->json([
                    'message' => 'Este correo electrónico ya está registrado en el sistema.',
                    'field' => 'email',
                ], 422);
            }
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $errors,
            ], 422);
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
            // Validación de las credenciales obligatorias
            $validated = $request->validate([
                'email' => 'required|string|email|max:100',
                'password' => 'required|string',
            ]);

            // Validación adicional para los parámetros opcionales
            $optionalData = $request->only([
                'device_id',
                'mobile_model',
                'os_version',
                'app_version',
                'network_type',
                'imei',
            ]);

            // Buscar el usuario por el correo
            $user = DB::table('mobile_users')->where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            // Verificar si el usuario es privado o público
            $location = DB::table('mobile_user_locations')
                ->where('user_id', $user->user_id)
                ->first();

            $stateName = null;
            $municipalityName = null;
            $isPrivateMunicipality = false;
            $url = 'https://www.radarciudadano.mx'; // URL pública por defecto

            if ($location) {
                if ($location->is_active) {
                    // Si el usuario está activo, verificar el municipio
                    $contractedMunicipality = DB::table('contracted_municipalities')
                        ->where('state_id', $location->state_id)
                        ->where('municipality_id', $location->municipality_id)
                        ->first();

                    if ($contractedMunicipality && $contractedMunicipality->is_private) {
                        // Municipio y usuario son privados
                        $isPrivateMunicipality = true;
                        $stateName = DB::table('estado')->where('EstadoId', $location->state_id)->value('EstadoNombre');
                        $municipalityName = DB::table('municipio')
                            ->where('EstadoId', $location->state_id)
                            ->where('MunicipioId', $location->municipality_id)
                            ->value('MunicipioNombre');
                        $url = $contractedMunicipality->url ?? 'https://www.radarciudadano.mx'; // URL del municipio contratado
                    } else {
                        // Municipio público, usuario se considera público
                        $location->is_active = 0; // Considerar como público
                    }
                } else {
                    // Usuario no está activo, pero conservar los datos de ubicación
                    $stateName = DB::table('estado')->where('EstadoId', $location->state_id)->value('EstadoNombre');
                    $municipalityName = DB::table('municipio')
                        ->where('EstadoId', $location->state_id)
                        ->where('MunicipioId', $location->municipality_id)
                        ->value('MunicipioNombre');
                }
            } else {
                // Usuario público sin datos de ubicación
                $location = (object) [
                    'user_id' => $user->user_id,
                    'state_id' => null,
                    'municipality_id' => null,
                    'is_active' => null,
                ];
            }

            // Generar un token para la sesión
            $token = Str::random(60);

            // Actualizar los campos opcionales y el token si existen
            $updateData = array_merge(['remember_token' => $token], $optionalData);

            DB::table('mobile_users')
                ->where('email', $validated['email'])
                ->update($updateData);

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
                    'is_private' => $location->is_active && $isPrivateMunicipality,
                    'url' => $url,
                    'location' => [
                        'user_id' => $location->user_id,
                        'state_id' => $location->state_id,
                        'municipality_id' => $location->municipality_id,
                        'is_active' => $location->is_active,
                        'state_name' => $stateName,
                        'municipality_name' => $municipalityName,
                    ],
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

    public function forgotPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:100|exists:mobile_users,email',
            ]);

            // Obtener información del usuario
            $user = DB::table('mobile_users')
                ->where('email', $validated['email'])
                ->first();

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Generar un código alfanumérico único
            $code = Str::upper(Str::random(8)); // Ejemplo: "A1B2C3D4"

            // Guardar el código en el campo 'token' de la tabla
            DB::table('mobile_password_resets')->updateOrInsert(
                ['email' => $validated['email']],
                [
                    'token' => $code,
                    'created_at' => now(),
                ]
            );

            // Enviar el correo con el código y los datos del usuario
            Mail::to($validated['email'])->send(new PasswordResetMail($code, $user));

            return response()->json(['message' => 'Se ha enviado un código a tu correo'], 200);
        } catch (\Exception $e) {
            Log::error('Error en la recuperación de contraseña: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al solicitar la recuperación de contraseña',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:100|exists:mobile_password_resets,email',
                'code' => 'required|string|max:8',
            ]);

            // Verificar si el código (token) es válido
            $record = DB::table('mobile_password_resets')
                ->where('email', $validated['email'])
                ->where('token', $validated['code'])
                ->first();

            if (!$record) {
                return response()->json(['error' => 'El código es inválido o ha expirado'], 401);
            }

            return response()->json(['message' => 'Código verificado correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al verificar el código: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al verificar el código',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:100|exists:mobile_users,email',
                'code' => 'required|string|max:8',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            // Verificar si el código (token) es válido
            $record = DB::table('mobile_password_resets')
                ->where('email', $validated['email'])
                ->where('token', $validated['code'])
                ->first();

            if (!$record) {
                return response()->json(['error' => 'El código es inválido o ha expirado'], 401);
            }

            // Actualizar la contraseña
            DB::table('mobile_users')
                ->where('email', $validated['email'])
                ->update([
                    'password' => bcrypt($validated['new_password']),
                    'updated_at' => now(),
                ]);

            // Eliminar el código después de usarlo
            DB::table('mobile_password_resets')->where('email', $validated['email'])->delete();

            // Enviar notificación por correo
            $user = DB::table('mobile_users')->where('email', $validated['email'])->first();
            Mail::to($validated['email'])->send(new PasswordResetSuccessMail($user));

            return response()->json(['message' => 'Contraseña restablecida exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al restablecer la contraseña: ' . $e->getMessage());
            return response()->json([
                'message' => 'Hubo un error al restablecer la contraseña',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function isPrivateMunicipality(Request $request)
    {
        $validatedData = $request->validate([
            'state_id' => 'required|integer|exists:contracted_municipalities,state_id',
            'municipality_id' => 'required|integer|exists:contracted_municipalities,municipality_id',
        ]);

        $stateId = $validatedData['state_id'];
        $municipalityId = $validatedData['municipality_id'];

        $municipality = DB::table('contracted_municipalities')
            ->where('state_id', $stateId)
            ->where('municipality_id', $municipalityId)
            ->first();

        if (!$municipality) {
            return response()->json(['message' => 'Municipio no encontrado'], 404);
        }

        return response()->json([
            'municipality' => $municipality->name,
            'is_private' => $municipality->is_private,
        ], 200);
    }

    public function asociation(Request $request)
    {
        // Validar que el municipio y estado sean válidos
        $validatedData = $request->validate([
            'state_id' => 'required|integer|exists:contracted_municipalities,state_id',
            'municipality_id' => 'required|integer|exists:contracted_municipalities,municipality_id',
        ]);

        // Obtener las asociaciones activas para el estado y municipio proporcionados
        $associations = DB::table('mobile_user_locations')
            ->join('mobile_users', 'mobile_user_locations.user_id', '=', 'mobile_users.user_id')
            ->join('estado', 'mobile_user_locations.state_id', '=', 'estado.EstadoId')
            ->join('municipio', function ($join) {
                $join->on('mobile_user_locations.state_id', '=', 'municipio.EstadoId')
                    ->on('mobile_user_locations.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->where('mobile_user_locations.state_id', $validatedData['state_id'])
            ->where('mobile_user_locations.municipality_id', $validatedData['municipality_id'])
            ->where('mobile_user_locations.is_active', 1) // Filtro para asociaciones activas
            ->select(
                'mobile_user_locations.id',
                'mobile_users.name as user_name',
                'mobile_users.email',
                'mobile_users.phone',
                'mobile_users.address',
                'mobile_users.occupation',
                'mobile_users.device_id',
                'mobile_users.mobile_model',
                'mobile_users.os_version',
                'mobile_users.app_version',
                'mobile_users.network_type',
                'mobile_users.imei',
                'estado.EstadoNombre as state_name',
                'municipio.MunicipioNombre as municipality_name',
                'mobile_user_locations.created_at'
            )
            ->get();

        return response()->json($associations, 200);
    }

    public function asociationStore(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:mobile_users,email',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:100',
                'state_id' => 'required|exists:estado,EstadoId',
                'municipality_id' => 'required|exists:municipio,MunicipioId',
            ]);

            // Iniciar una transacción para asegurar la consistencia de los datos
            DB::beginTransaction();

            // Registrar el nuevo usuario en la tabla 'mobile_users'
            $userId = DB::table('mobile_users')->insertGetId([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']), // Hashear la contraseña
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'occupation' => $validatedData['occupation'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Verificar si ya existe la asociación en 'mobile_user_locations'
            $existingAssociation = DB::table('mobile_user_locations')
                ->where('user_id', $userId)
                ->where('state_id', $validatedData['state_id'])
                ->where('municipality_id', $validatedData['municipality_id'])
                ->first();

            if ($existingAssociation) {
                // Si ya existe, revertir la transacción
                DB::rollBack();
                return response()->json(['message' => 'La asociación ya existe.'], 409); // Código 409: Conflicto
            }

            // Insertar la nueva asociación en 'mobile_user_locations' con is_active = 1
            DB::table('mobile_user_locations')->insert([
                'user_id' => $userId,
                'state_id' => $validatedData['state_id'],
                'municipality_id' => $validatedData['municipality_id'],
                'is_active' => 1, // Activo por defecto
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Usuario y asociación creados exitosamente.'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y retornar en formato JSON
            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422); // Código 422: Unprocessable Entity
        } catch (\Exception $e) {
            // En caso de error general, revertir la transacción
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el usuario y la asociación.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function asociationDelete($id)
    {
        // Buscar la asociación en 'mobile_user_locations'
        $association = DB::table('mobile_user_locations')->where('id', $id)->first();

        if (!$association) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        // Actualizar el estado de 'is_active' a 0 en lugar de eliminar
        DB::table('mobile_user_locations')->where('id', $id)->update([
            'is_active' => 0, // Desactivar la asociación
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Asociación desactivada exitosamente'], 200);
    }

    public function asociationAvailableUsers(Request $request)
    {
        // Validar que el municipio y estado sean válidos
        $validatedData = $request->validate([
            'state_id' => 'required|integer|exists:contracted_municipalities,state_id',
            'municipality_id' => 'required|integer|exists:contracted_municipalities,municipality_id',
        ]);

        // Obtener los usuarios disponibles (is_active = 0) para el estado y municipio proporcionados
        $availableUsers = DB::table('mobile_user_locations')
            ->join('mobile_users', 'mobile_user_locations.user_id', '=', 'mobile_users.user_id')
            ->join('estado', 'mobile_user_locations.state_id', '=', 'estado.EstadoId')
            ->join('municipio', function ($join) {
                $join->on('mobile_user_locations.state_id', '=', 'municipio.EstadoId')
                    ->on('mobile_user_locations.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->where('mobile_user_locations.state_id', $validatedData['state_id'])
            ->where('mobile_user_locations.municipality_id', $validatedData['municipality_id'])
            ->where('mobile_user_locations.is_active', 0) // Filtro para usuarios disponibles
            ->select(
                'mobile_user_locations.id',
                'mobile_users.user_id',
                'mobile_users.name as user_name',
                'mobile_users.email',
                'mobile_users.phone',
                'mobile_users.address',
                'mobile_users.occupation',
                'mobile_users.device_id',
                'mobile_users.mobile_model',
                'mobile_users.os_version',
                'mobile_users.app_version',
                'mobile_users.network_type',
                'mobile_users.imei',
                'estado.EstadoNombre as state_name',
                'municipio.MunicipioNombre as municipality_name',
                'mobile_user_locations.created_at'
            )
            ->get();

        return response()->json($availableUsers, 200);
    }

    public function asociationReactivate(Request $request)
    {
        // Registrar todos los datos recibidos del request
        Log::info('Datos recibidos en la solicitud de reactivación de asociación:', $request->all());

        // Validar los datos de entrada
        $validatedData = $request->validate([
            'user_id' => 'required|exists:mobile_users,user_id', // Verifica que el usuario exista en mobile_users
            'state_id' => 'required|exists:estado,EstadoId',     // Verifica que el estado exista
            'municipality_id' => 'required|exists:municipio,MunicipioId', // Verifica que el municipio exista
        ]);

        // Registrar datos validados
        Log::info('Datos validados:', $validatedData);

        // Buscar la asociación inactiva
        $association = DB::table('mobile_user_locations')
            ->where('user_id', $validatedData['user_id'])
            ->where('state_id', $validatedData['state_id'])
            ->where('municipality_id', $validatedData['municipality_id'])
            ->where('is_active', 0) // Debe estar inactiva
            ->first();

        if (!$association) {
            Log::warning('No se encontró una asociación inactiva.', [
                'user_id' => $validatedData['user_id'],
                'state_id' => $validatedData['state_id'],
                'municipality_id' => $validatedData['municipality_id'],
            ]);
            return response()->json(['message' => 'No se encontró una asociación inactiva para este usuario, estado y municipio.'], 404);
        }

        // Registrar que se encontró la asociación inactiva
        Log::info('Asociación encontrada:', (array) $association);

        // Reactivar la asociación configurando is_active = 1
        DB::table('mobile_user_locations')
            ->where('id', $association->id)
            ->update([
                'is_active' => 1,
                'updated_at' => now(),
            ]);

        // Registrar que la asociación fue reactivada
        Log::info('Asociación reactivada exitosamente.', ['association_id' => $association->id]);

        return response()->json(['message' => 'Asociación reactivada exitosamente.'], 200);
    }
}
