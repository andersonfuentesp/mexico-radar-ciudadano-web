<?php

namespace Database\Seeders;

use App\Models\MunicipalityService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalityServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Suponiendo que ya existe un municipio con ID 1 y 2 en la tabla contracted_municipalities
        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/noticias',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar noticias',
            'description' => 'Servicio para obtener noticias.',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/tramites',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar tramites',
            'description' => 'Servicio para obtener tramites',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/terms',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar terminos',
            'description' => 'Servicio para obtener terminos',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/numeros',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar numeros de emergencia',
            'description' => 'Servicio para obtener numeros de emergencia',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/reportes',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar reportes',
            'description' => 'Servicio para obtener reportes',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/reportes/detail',
            'method' => 'POST',
            'response_format' => 'JSON',
            'service_name' => 'Obtener datos de reporte',
            'description' => 'Servicio para obtener datos de reporte',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/reporte/store',
            'method' => 'POST',
            'response_format' => 'JSON',
            'service_name' => 'Ingresar reporte',
            'description' => 'Servicio para ingresar reporte',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/colonias',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar colonias',
            'description' => 'Servicio para obtener colonias',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/tipo-reporte',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar tipo de reporte',
            'description' => 'Servicio para obtener tipos de reporte',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/tipo-reporte',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar tipo de reporte por municipio',
            'description' => 'Servicio para obtener tipos de reporte por municipio',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/estatus-reporte',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar estatus de reporte',
            'description' => 'Servicio para obtener estatus de reporte',
            'status' => true,
        ]);

        MunicipalityService::create([
            'municipality_id' => 1,
            'api_url' => '/api/directorios',
            'method' => 'GET',
            'response_format' => 'JSON',
            'service_name' => 'Listar directorios',
            'description' => 'Servicio para obtener directorios',
            'status' => true,
        ]);

    }
}
