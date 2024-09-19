<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DefaultController extends Controller
{
    public function proxyGoogleMaps(Request $request)
    {
        // Asegurarte de que la solicitud proviene de tu propio dominio
        $referer = $request->headers->get('referer');
        $allowedDomain = config('services.google.app_url_google');

        if (substr($referer, 0, strlen($allowedDomain)) !== $allowedDomain) {
            return response()->json(['error' => 'Acceso no autorizado'], 403);
        }

        // Construye la URL de solicitud a la API de Google Maps
        $mapsApiUrl = 'https://maps.googleapis.com/maps/api/js';
        $params = [
            'key' => config('services.google.maps_api_key'),
            'callback' => 'initMap',
            'libraries' => 'places,drawing',
            'v' => '3.55', // Especifica la versión sin deprecaciones
        ];
        $apiResponse = Http::get($mapsApiUrl, $params);

        // Devuelve la respuesta de la API de Google Maps
        if ($apiResponse->successful()) {
            return $apiResponse->body();
        } else {
            return response()->json(['error' => 'Error al contactar la API de Google Maps'], 500);
        }
    }

    public function geocodeAddress(Request $request)
    {
        // Extraer la dirección del request
        $address = $request->input('address');

        // Construye la URL de la API de Geocodificación de Google Maps
        $geocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $params = [
            'address' => $address,
            'key' => config('services.google.maps_api_key'),
        ];

        $apiResponse = Http::get($geocodeApiUrl, $params);

        // Devuelve la respuesta de la API de Geocodificación
        if ($apiResponse->successful()) {
            $location = $apiResponse->json()['results'][0]['geometry']['location'];
            return response()->json(['latitude' => $location['lat'], 'longitude' => $location['lng']]);
        } else {
            return response()->json(['error' => 'Error al contactar la API de Geocodificación de Google Maps'], 500);
        }
    }
}
