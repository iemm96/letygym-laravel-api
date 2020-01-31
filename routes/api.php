<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/asistenciasSocios','AsistenciaController@getRecords');

Route::get('/pagosSocios','SociosMembresiasController@getRecords');
Route::post('/pagosSocios','PagosController@storeRecord');

Route::post('/socio','SociosController@storeRecord');

Route::get('/compruebaRenovaciones','SociosMembresiasController@getRenovaciones');
Route::get('/sociosMembresias','SociosMembresiasController@getRecords');
Route::get('/sociosyvisitantes','SociosMembresiasController@getSociosyVisitantes');
Route::get('/socioMembresia/{id}','SociosMembresiasController@getSocioMembresiaById');
Route::put('/socioMembresia/{id}','SociosMembresiasController@updateRecord');

Route::get('/ventasProductos','VentasController@getRecords');
Route::delete('/ventasProductos/{id}','VentasController@deleteRecord');
Route::post('/ventasProductos','VentasController@storeRecord');

Route::get('/pagosSocios','PagosController@getRecords');
Route::post('/pagosSocios','PagosController@storeRecord');

Route::delete('/visitantesVisitas/{id}','VisitantesVisitasController@deleteRecord');
Route::get('/visitantesVisitas','VisitantesVisitasController@getRecords');
Route::post('/visitantesVisitas','VisitantesVisitasController@storeRecord');

Route::resource('asistencias','AsistenciaController');
Route::resource('membresias','MembresiasController');
Route::resource('pagos','PagosController');
Route::resource('productos','ProductosController');
Route::resource('socios','SociosController');
//Route::resource('sociosMembresias','SociosMembresiasController');
Route::resource('statusSocios','StatusSociosController');
Route::resource('tiposMembresias','TiposMembresiasController');
Route::resource('ventas','VentasController');
//Route::resource('visitantesVisitas','VisitantesVisitasController');

