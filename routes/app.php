<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\ContractedMunicipalityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DefaultController;
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

/************* Admin: Contracted Municipalities ***************/
Route::controller(ContractedMunicipalityController::class)->group(function () {
    Route::get('municipios-contratados', 'index')->name('admin.contractedMunicipality.all');
    Route::get('municipios-contratados/agregar', 'create')->name('admin.contractedMunicipality.add');
    Route::post('municipios-contratados/store', 'store')->name('admin.contractedMunicipality.store');
    Route::get('municipios-contratados/detalle/{id}', 'detail')->name('admin.contractedMunicipality.detail');
    Route::get('municipios-contratados/editar/{id}', 'edit')->name('admin.contractedMunicipality.edit');
    Route::post('municipios-contratados/update/{id}', 'update')->name('admin.contractedMunicipality.update');
    Route::get('municipios-contratados/delete/{id}', 'destroy')->name('admin.contractedMunicipality.delete');
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