<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class LocationHelper
{
    public static function obtenerColoniaYCodigoPostal($ubicacionGps)
    {
        $reverseGeocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $reverseParams = [
            'latlng' => $ubicacionGps,
            'key' => config('services.google.maps_api_key'),
        ];

        $reverseApiResponse = Http::get($reverseGeocodeApiUrl, $reverseParams);

        $colonia = '';
        $codigoPostal = '';

        if ($reverseApiResponse->successful()) {
            $reverseResults = $reverseApiResponse->json()['results'][0]['address_components'];

            foreach ($reverseResults as $component) {
                if (in_array('sublocality_level_1', $component['types'])) {
                    $colonia = $component['long_name'];
                }

                if (in_array('postal_code', $component['types'])) {
                    $codigoPostal = $component['long_name'];
                }
            }

            return ['colonia' => $colonia, 'codigoPostal' => $codigoPostal];
        }

        return ['error' => 'Error al obtener la dirección detallada.'];
    }

    public static function obtenerDireccionCompleta($latitude, $longitude)
    {
        $reverseGeocodeApiUrl = 'https://maps.googleapis.com/maps/api/geocode/json';
        $reverseParams = [
            'latlng' => "{$latitude},{$longitude}",
            'key' => config('services.google.maps_api_key'),
        ];

        // Realizar la solicitud a la API de Google
        $response = Http::get($reverseGeocodeApiUrl, $reverseParams);

        if ($response->successful()) {
            $addressComponents = $response->json()['results'][0]['address_components'];
            $formattedAddress = $response->json()['results'][0]['formatted_address'];

            $colonia = '';
            $codigoPostal = '';

            // Extraer colonia y código postal
            foreach ($addressComponents as $component) {
                if (in_array('sublocality_level_1', $component['types'])) {
                    $colonia = $component['long_name'];
                }
                if (in_array('postal_code', $component['types'])) {
                    $codigoPostal = $component['long_name'];
                }
            }

            // Concatenar los valores en un string
            $direccionCompleta = $formattedAddress;
            if ($colonia) {
                $direccionCompleta .= ', Colonia ' . $colonia;
            }
            if ($codigoPostal) {
                $direccionCompleta .= ', Código Postal ' . $codigoPostal;
            }

            return $direccionCompleta;
        }

        return 'Error al obtener la dirección detallada.';
    }

    public static function convertPolygonToLatLng($polygon)
    {
        $coordinates = [];
        $points = array_map('trim', explode(',', trim(str_replace(['POLYGON((', '))'], '', $polygon))));
        foreach ($points as $point) {
            list($lng, $lat) = array_map('trim', explode(' ', $point));
            $coordinates[] = ['lat' => (float) $lat, 'lng' => (float) $lng];
        }
        return $coordinates;
    }

    public static function isPointInPolygon($point, $polygon)
    {
        $c = false;
        $l = count($polygon);
        $j = $l - 1;
        for ($i = 0; $i < $l; $j = $i++) {
            if ((($polygon[$i]['lng'] > $point['lng']) !== ($polygon[$j]['lng'] > $point['lng'])) &&
                ($point['lat'] < ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($point['lng'] - $polygon[$i]['lng']) / ($polygon[$j]['lng'] - $polygon[$i]['lng']) + $polygon[$i]['lat'])
            ) {
                $c = !$c;
            }
        }
        return $c;
    }
}
