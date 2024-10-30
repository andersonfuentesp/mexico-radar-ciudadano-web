<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/************* Api Controller ***************/
Route::controller(ApiController::class)->group(function () {
    Route::get('/municipios/{estadoId}', 'getMunicipiosByEstado');
    Route::get('/estados', 'getEstados');

    Route::get('/noticias', 'getNoticias');
    Route::get('/tramites', 'getTramites');
    Route::get('/terms', 'getTerms');
    Route::get('/numeros', 'getEmergencyNumbers');
    Route::get('/emergency-numbers-by-location', 'getEmergencyNumbersByLocation');

    // Nueva ruta para guardar el reporte
    Route::get('/report', 'getReport');
    Route::post('/save-report', 'saveReport');
    Route::post('/save-simple-report', 'saveSimpleReport');

    Route::post('/update-report', 'updateReport');
    Route::post('/report/consulta', 'reportConsulta');

    Route::get('/tipo-reporte', 'getTipoReporte');
    Route::post('/tipo-reporte-municipio', 'getTipoReporteMunicipio');

    Route::post('/estatus-reporte', 'getEstatusReporte');

    Route::post('/report-user', 'reportUser');
    Route::post('/search-user-reports', 'searchUserReports');

    Route::post('/user/register', 'registerUser');
    Route::post('/user/login', 'loginUser');
    Route::put('/user/update', 'updateUser');
    Route::put('/user/update-password', 'updatePassword');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
