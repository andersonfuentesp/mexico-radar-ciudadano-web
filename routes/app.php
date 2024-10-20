<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Catalogo\CatalogoEstadoController;
use App\Http\Controllers\Catalogo\CatalogoMunicipioController;
use App\Http\Controllers\Catalogo\CatalogoTipoReporteController;
use App\Http\Controllers\ContractedMunicipalityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Catalogo\CatalogoColoniaController;
use App\Http\Controllers\Catalogo\CatalogoEstatusReporteController;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\Reporte\ReporteController;
use App\Http\Controllers\UtilitieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/maps-api-proxy', [DefaultController::class, 'proxyGoogleMaps'])->name('maps.proxy');
Route::get('/maps-api-proxy-geocodification', [DefaultController::class, 'geocodeAddress'])->name('maps.proxy.geocodificacion');
Route::get('/maps-api-proxy-geocodification-coordinates', [DefaultController::class, 'getPolygonCoordinates'])->name('maps.proxy.geocodificacion.polygon');
Route::post('/maps-api-proxy-geocodification-postal-code-neighborhood', [DefaultController::class, 'getColoniaYCodigoPostal'])->name('maps.proxy.geocodificacion.postal-code-neighborhood');

/************* Admin ***************/
Route::controller(UtilitieController::class)->group(function () {
    Route::get('utilitie/municipios/{estadoId}', 'getMunicipiosByEstado')->name('admin.utilitie.getMunicipiosByEstado');
});

Route::controller(CommonController::class)->group(function () {
    Route::get('', 'index')->name('admin.index');
    Route::post('/chart', 'chart')->name('admin.chart');

    Route::get('mi-perfil', 'profile')->name('admin.profile');

    Route::get('mi-perfil/editar', 'profileEdit')->name('admin.profile.edit');
    Route::post('profile/update', 'profileUpdate')->name('admin.profile.update');

    Route::get('seguridad', 'passwordChange')->name('admin.password.change');
    Route::post('password/update', 'passwordUpdate')->name('admin.password.update');
});

/************* Admin: Catalog Tipo Reporte ***************/
Route::controller(CatalogoTipoReporteController::class)->group(function () {
    Route::get('catalogo/tipo-reporte', 'reportsType')->name('admin.catalog.reports.type');
    Route::get('catalogo/tipo-reporte/agregar', 'addReportType')->name('admin.catalog.reports.type.add');
    Route::post('catalogo/tipo-reporte/store', 'storeReportType')->name('admin.catalog.reports.type.store');
    Route::get('catalogo/tipo-reporte/editar/{id}', 'editReportType')->name('admin.catalog.reports.type.edit');
    Route::put('catalogo/tipo-reporte/update/{id}', 'updateReportType')->name('admin.catalog.reports.type.update');
    Route::get('catalogo/tipo-reporte/detalle/{id}', 'detailReportType')->name('admin.catalog.reports.type.detail');
    Route::delete('catalogo/tipo-reporte/delete/{id}', 'deleteReportType')->name('admin.catalog.reports.type.delete');
});

Route::controller(CatalogoEstadoController::class)->group(function () {
    Route::get('catalogo/estados', 'states')->name('admin.catalog.states');
    //Route::get('catalogo/estados/editar/{id}', 'stateEditar')->name('admin.catalog.state.edit');
    Route::get('catalogo/estados/{estadoPolId}/{estadoPolNumero}', 'stateDetail')->name('admin.catalog.state.detail');
});

Route::controller(CatalogoMunicipioController::class)->group(function () {
    Route::get('catalogo/municipios', 'municipalities')->name('admin.catalog.municipalities');
    Route::get('catalogo/municipios/{estadoPolId}/{municipioPolId}/{municipioPolNumero}', 'municipalityDetail')->name('admin.catalog.municipality.detail');
});

Route::controller(CatalogoColoniaController::class)->group(function () {
    Route::get('catalogo/colonias', 'neighborhoods')->name('admin.catalog.neighborhoods');
    /*
    Route::get('catalogo/colonias/agregar', 'addNeighborhood')->name('admin.catalog.neighborhood.add');
    Route::post('catalogo/colonias/store', 'storeNeighborhood')->name('admin.catalog.neighborhood.store');
    Route::get('catalogo/colonias/editar/{id}', 'editNeighborhood')->name('admin.catalog.neighborhood.edit');
    Route::put('catalogo/colonias/update/{id}', 'updateNeighborhood')->name('admin.catalog.neighborhood.update');
    Route::delete('catalogo/colonias/delete/{state_id}/{municipality_id}/{neighborhood_id}', 'deleteNeighborhood')->name('admin.catalog.neighborhood.delete');
    */
});

Route::controller(CatalogoEstatusReporteController::class)->group(function () {
    Route::get('catalogo/reporte-estatus', 'reportStatus')->name('admin.catalog.report.status');
    Route::get('catalogo/reporte-estatus/agregar', 'addReportStatus')->name('admin.catalog.report.status.add');
    Route::post('catalogo/reporte-estatus/store', 'storeReportStatus')->name('admin.catalog.report.status.store');
    Route::delete('catalogo/reporte-estatus/delete/{id}', 'deleteReportStatus')->name('admin.catalog.report.status.delete');
});

/************* Admin: Contracted Municipalities ***************/
Route::controller(ContractedMunicipalityController::class)->group(function () {
    Route::get('municipios-contratados', 'index')->name('admin.contractedMunicipality.all');
    Route::get('municipios-contratados/agregar', 'create')->name('admin.contractedMunicipality.add');
    Route::post('municipios-contratados/store', 'store')->name('admin.contractedMunicipality.store');
    Route::get('municipios-contratados/detalle/{id}', 'detail')->name('admin.contractedMunicipality.detail');
    Route::get('municipios-contratados/editar/{id}', 'edit')->name('admin.contractedMunicipality.edit');
    Route::put('municipios-contratados/update/{id}', 'update')->name('admin.contractedMunicipality.update');
    Route::delete('municipios-contratados/delete/{id}', 'destroy')->name('admin.contractedMunicipality.delete');

    Route::get('municipios-contratados/servicios/{id}', 'services')->name('admin.contractedMunicipality.services');
    Route::get('municipios-contratados/servicios/{id}/agregar', 'servicesAdd')->name('admin.contractedMunicipality.services.add');
    Route::post('municipios-contratados/servicios/{id}/store', 'servicesStore')->name('admin.contractedMunicipality.services.store');
    Route::get('municipios-contratados/servicios/{id}/detalle', 'servicesDetail')->name('admin.contractedMunicipality.services.detail');
    Route::get('municipios-contratados/servicios/{municipalityId}/editar/{serviceId}', 'servicesEdit')->name('admin.contractedMunicipality.services.edit');
    Route::put('municipios-contratados/servicios/{municipalityId}/update/{serviceId}', 'servicesUpdate')->name('admin.contractedMunicipality.services.update');
    Route::delete('municipios-contratados/servicios/{municipalityId}/delete/{serviceId}', 'servicesDestroy')->name('admin.contractedMunicipality.services.delete');
});

Route::controller(ReporteController::class)->group(function () {
    Route::get('reportes', 'reporteCiudadano')->name('admin.reports.all');
    Route::get('reportes/detalle/{report_id}/{state_id}/{municipality_id}', 'detailReporteCiudadano')
        ->name('admin.reports.detail');

    Route::get('reportes/agregar', 'addReporteCiudadano')->name('admin.reports.add');
    Route::post('reportes/store', 'storeReporteCiudadano')->name('admin.reports.store');

    Route::get('reportes/editar/{id}', 'editReporteCiudadano')->name('admin.reports.edit');
    Route::put('reportes/update/{id}', 'updateReporteCiudadano')->name('admin.reports.update');
    Route::delete('reportes/delete/{id}', 'deleteReporteCiudadano')->name('admin.reports.delete');
});

Route::controller(UserController::class)->group(function () {
    Route::get('gestion-usuarios', 'userAll')->name('admin.user.all');

    Route::get('gestion-usuarios/agregar', 'userAdd')->name('admin.user.add');
    Route::post('user/store', 'userStore')->name('admin.user.store');
    Route::get('gestion-usuarios/editar/{id}', 'userEdit')->name('admin.user.edit');
    Route::post('user/update/{id}', 'userUpdate')->name('admin.user.update');
    Route::get('gestion-usuarios/detalle/{id}', 'userDetail')->name('admin.user.detail');
    Route::get('user/delete/{id}', 'userDelete')->name('admin.user.delete');

    Route::get('gestion-usuarios/tipo-reporte', 'userReportType')->name('admin.user.report.type');
});

// All Roles Routes
Route::controller(RoleController::class)->group(function () {
    Route::get('role', 'roleAll')->name('admin.role.all');
    Route::get('role/add', 'roleAdd')->name('admin.role.add');
    Route::post('role/store', 'roleStore')->name('admin.role.store');

    Route::get('role/edit/{id}', 'roleEdit')->name('admin.role.edit');
    Route::post('role/update/{id}', 'roleUpdate')->name('admin.role.update');
    Route::get('role/delete/{id}', 'roleDelete')->name('admin.role.delete');
});

// All Assignment Routes
Route::controller(AssignmentController::class)->group(function () {
    Route::get('assignment', 'assignmentAll')->name('admin.assignment.all');
    Route::get('assignment/edit/{id}', 'assignmentEdit')->name('admin.assignment.edit');

    Route::post('assignment/update/{id}', 'assignmentUpdate')->name('admin.assignment.update');
});