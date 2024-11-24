<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

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

/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::controller(WebsiteController::class)->group(function () {
    Route::get('/', 'index')->name('website.index');
    Route::get('/nosotros', 'nosotros')->name('website.nosotros');
    Route::get('/nosotros-empresa', 'nosotrosEmpresa')->name('website.nosotros-company');

    Route::get('/servicios', 'servicios')->name('website.servicios');
    Route::get('/galeria', 'galeria')->name('website.galeria');

    Route::get('/servicios/cartografia', 'serviciosCartografia')->name('website.servicios.cartografia');
    Route::get('/servicios/aplicaciones', 'serviciosAplicaciones')->name('website.servicios.aplicaciones');
    Route::get('/servicios/mapas-satelitales', 'serviciosMapas')->name('website.servicios.mapas');
    Route::get('/servicios/analisis-de-datos', 'serviciosAnalisis')->name('website.servicios.analisis');

    Route::get('/cotizacion', 'cotizacion')->name('website.cotizacion');
    Route::post('/cotizacion-send', 'cotizacionStore')->name('website.cotizacion.send');

    Route::get('/contacto', 'contacto')->name('website.contacto');
    Route::post('/contact-send', 'contactoStore')->name('website.contacto.send');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
