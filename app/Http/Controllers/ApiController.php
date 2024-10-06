<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\MunicipioPol;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getEstados()
    {
        $estados = Estado::all();
        return response()->json($estados);
    }

    public function getMunicipiosByEstado($estadoId)
    {
        $municipios = Municipio::where('EstadoId', $estadoId)->get();
        return response()->json($municipios);
    }

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
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
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

        // Agregar los filtros de vigencia si están presentes
        if ($request->has('search_vigencia_inicial') && !empty($request->search_vigencia_inicial)) {
            $params['search_vigencia_inicial'] = $request->search_vigencia_inicial;
        }

        if ($request->has('search_vigencia_final') && !empty($request->search_vigencia_final)) {
            $params['search_vigencia_final'] = $request->search_vigencia_final;
        }

        // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
        if (strtoupper($municipalityService->method) === 'POST') {
            $response = Http::withHeaders($headers)->post($apiUrl, $params);
        } else {
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
        }

        // 8. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudo obtener las noticias. Verifica la URL del servicio o la configuración.'], 500);
        }
    }

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
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
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

        // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
        if (strtoupper($municipalityService->method) === 'POST') {
            $response = Http::withHeaders($headers)->post($apiUrl, $params);
        } else {
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
        }

        // 8. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudo obtener los trámites. Verifica la URL del servicio o la configuración.'], 500);
        }
    }

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
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
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

        // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
        if (strtoupper($municipalityService->method) === 'POST') {
            $response = Http::withHeaders($headers)->post($apiUrl, $params);
        } else {
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
        }

        // 8. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudo obtener los términos. Verifica la URL del servicio o la configuración.'], 500);
        }
    }

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
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
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

        // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
        if (strtoupper($municipalityService->method) === 'POST') {
            $response = Http::withHeaders($headers)->post($apiUrl, $params);
        } else {
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
        }

        // 8. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudo obtener los números de emergencia. Verifica la URL del servicio o la configuración.'], 500);
        }
    }

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
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
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

        // 7. Realizar la solicitud HTTP dependiendo del método (GET o POST)
        if (strtoupper($municipalityService->method) === 'POST') {
            $response = Http::withHeaders($headers)->post($apiUrl, $params);
        } else {
            $response = Http::withHeaders($headers)->get($apiUrl, $params);
        }

        // 8. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'No se pudo obtener los reportes. Verifica la URL del servicio o la configuración.'], 500);
        }
    }

    public function saveReport(Request $request)
    {
        $latitud = $request->input('latitud');
        $longitud = $request->input('longitud');

        // 1. Consultar el polígono usando el campo MunicipioPolChar para encontrar si contiene el punto
        $municipioPol = DB::table('MunicipioPol')
            ->whereRaw("ST_Contains(ST_GeomFromText(MunicipioPolChar, 4326), ST_GeomFromText('POINT($longitud $latitud)', 4326))")
            ->first();

        if (!$municipioPol) {
            return response()->json(['error' => 'No se encontró un municipio para las coordenadas proporcionadas'], 404);
        }

        // 2. Buscar en la tabla contracted_municipalities
        $contractedMunicipality = DB::table('contracted_municipalities')
            ->where('state_id', $municipioPol->EstadoPolId)
            ->where('municipality_id', $municipioPol->MunicipioPolId)
            ->first();

        if (!$contractedMunicipality) {
            return response()->json(['error' => 'No se encontró un municipio contratado para las coordenadas proporcionadas'], 404);
        }

        // 3. Buscar en la tabla municipality_services filtrando por service_name = 'Ingresar reporte'
        $municipalityService = DB::table('municipality_services')
            ->where('municipality_id', $contractedMunicipality->id)
            ->where('service_name', 'Ingresar reporte')
            ->first();

        if (!$municipalityService) {
            return response()->json(['error' => 'No se encontró un servicio para ingresar reporte en el municipio'], 404);
        }

        // 4. Concatenar la URL base del municipio con la API URL del servicio
        $apiUrl = rtrim($contractedMunicipality->url, '/') . '/' . ltrim($municipalityService->api_url, '/');

        // 5. Realizar la solicitud HTTP POST a la URL concatenada
        $response = Http::post($apiUrl, [
            'latitud' => $latitud,
            'longitud' => $longitud,
            'estado' => $request->input('estado'),
            'municipio' => $request->input('municipio'),
            'reportType' => $request->input('reportType'),
            'direccion' => $request->input('direccion'),
            'comentario' => $request->input('comentario'),
            'telefono' => $request->input('telefono'),
            'correoElectronico' => $request->input('correoElectronico'),
            'ubicacionGps' => $request->input('ubicacionGps'),
            // Agregar la ruta de la imagen si está disponible
            'fotografia' => $request->hasFile('fotografia') ? base64_encode(file_get_contents($request->file('fotografia')->getRealPath())) : null,
        ]);

        // 6. Devolver la respuesta de la solicitud HTTP
        if ($response->successful()) {
            return response()->json([
                'message' => 'Reporte ingresado con éxito',
                'data' => $response->json()
            ]);
        } else {
            return response()->json(['error' => 'No se pudo ingresar el reporte. Verifica la URL del servicio o la configuración.'], 500);
        }
    }
}
