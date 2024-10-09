<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CatalogoColoniaController extends Controller
{
    public function neighborhoods(Request $request)
    {
        // Consultar los municipios contratados con su estado y municipio
        $municipalities = DB::table('contracted_municipalities')
            ->leftJoin('estado', 'contracted_municipalities.state_id', '=', 'estado.EstadoId')
            ->leftJoin('municipio', function ($join) {
                $join->on('contracted_municipalities.state_id', '=', 'municipio.EstadoId')
                    ->on('contracted_municipalities.municipality_id', '=', 'municipio.MunicipioId');
            })
            ->leftJoin('municipality_services', 'contracted_municipalities.id', '=', 'municipality_services.municipality_id')
            ->where('municipality_services.service_name', 'Listar colonias')
            ->select(
                'contracted_municipalities.id',
                'contracted_municipalities.state_id',
                'contracted_municipalities.municipality_id',
                'contracted_municipalities.name as municipio',
                'estado.EstadoNombre',
                'municipio.MunicipioNombre',
                'contracted_municipalities.url as municipality_url', // URL
                'contracted_municipalities.token', // Agregar el token del municipio
                'municipality_services.api_url as service_url',
                'municipality_services.method',
                'municipality_services.status as service_status'
            )
            ->get();

        $neighborhoodsData = [];

        // Hacer la petición a cada municipio que tenga el servicio "Listar colonias"
        foreach ($municipalities as $municipality) {
            // Preparar la URL completa de la API
            $apiUrl = rtrim($municipality->municipality_url, '/') . '/' . ltrim($municipality->service_url, '/');

            // Preparar los headers con el token
            $headers = [
                'Authorization' => 'Bearer ' . $municipality->token,
                'Content-Type' => 'application/json',
            ];

            // Preparar los parámetros que se enviarán en el request
            $params = [
                'estado_id' => $municipality->state_id,
                'municipio_id' => $municipality->municipality_id
            ];

            // Hacer la petición HTTP (GET o POST según el método)
            if (strtoupper($municipality->method) === 'POST') {
                $response = Http::withHeaders($headers)->post($apiUrl, $params);
            } else {
                $response = Http::withHeaders($headers)->get($apiUrl, $params);
            }

            // Si la respuesta es exitosa, guardar la data
            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $neighborhood) {
                        $neighborhoodsData[] = [
                            'state_id' => $neighborhood['state_id'],
                            'municipality_id' => $neighborhood['municipality_id'],
                            'neighborhood_id' => $neighborhood['neighborhood_id'],
                            'neighborhood_name' => $neighborhood['neighborhood_name'],
                            'postal_code' => $neighborhood['postal_code'],
                            'created_at' => $neighborhood['created_at'], // Fecha de creación
                            'EstadoNombre' => $neighborhood['EstadoNombre'],
                            'MunicipioNombre' => $neighborhood['MunicipioNombre'],
                            'municipality_url' => $municipality->municipality_url // URL de la tabla contracted_municipalities
                        ];
                    }
                }
            }
        }

        // Paginación de los resultados
        $currentPage = $request->get('page', 1);
        $perPage = 100;
        $paginatedData = collect($neighborhoodsData)->forPage($currentPage, $perPage);

        return view('app.catalog.neighborhoods', [
            'neighborhoods' => $paginatedData,
            'total' => count($neighborhoodsData),
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ]);
    }
}
