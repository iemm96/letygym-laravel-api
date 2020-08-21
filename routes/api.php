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
Route::get('/asistenciasInstructores/getRecords','AsistenciasInstructoresController@getRecords');

Route::get('/pagosSocios','SociosMembresiasController@getRecords');
Route::get('/getTurnoActual','AppStatusController@getTurnoActual');
Route::put('/updateTurnoActual/{turno}','AppStatusController@updateTurnoActual');
Route::get('/ingresos/getRecords/{turno}/{fecha?}','IngresosController@getRecords');
Route::get('/ingresos/getTotal/{fecha?}','IngresosController@getTotal');
Route::get('/transacciones/getTotal/{fecha?}','TransaccionesController@getTotal');
Route::get('/transacciones/getRecords/{turno?}/{fecha?}/{tipo?}','TransaccionesController@getRecords');
Route::post('/pagosSocios','PagosController@storeRecord');

Route::post('productos/addRecord','ProductosController@addRecord');

Route::post('/socio','SociosController@storeRecord');
Route::put('/socio/{id}','SociosController@updateRecord');
Route::post('/ventasProductos','VentasController@storeRecord');

Route::get('/compruebaRenovaciones','SociosMembresiasController@getRenovaciones');
Route::get('/sociosMembresias','SociosMembresiasController@getRecords');
Route::get('/socioMembresias/{id}','SociosMembresiasController@getRecord');
Route::get('/sociosyvisitantes','SociosMembresiasController@getSociosyVisitantes');
Route::get('/socioMembresia/{id}','SociosMembresiasController@getSocioMembresiaById');
Route::get('/socioMembresia/hasMembresiaActiva/{id}','SociosMembresiasController@hasMembresiaActiva');
Route::put('/socioMembresia/{id}','SociosMembresiasController@updateRecord');

Route::get('/ventasProductos','VentasController@getRecords');
Route::delete('/ventasProductos/{id}','VentasController@deleteRecord');

Route::get('/pagosSocios','PagosController@getRecords');
Route::post('/pagosSocios','PagosController@storeRecord');

Route::delete('/visitantesVisitas/{id}','VisitantesVisitasController@deleteRecord');
Route::get('/visitantesVisitas','VisitantesVisitasController@getRecords');
Route::post('/visitantesVisitas','VisitantesVisitasController@storeRecord');

Route::resource('asistencias','AsistenciaController');
Route::resource('asistenciasInstructores','AsistenciasInstructoresController');
Route::resource('membresias','MembresiasController');
Route::resource('pagos','PagosController');
Route::resource('productos','ProductosController');
Route::resource('socios','SociosController');
Route::resource('statusSocios','StatusSociosController');
Route::resource('tiposMembresias','TiposMembresiasController');
Route::resource('ventas','VentasController');
Route::resource('ingresos','IngresosController');
Route::resource('instructores','InstructoresController');
Route::resource('transacciones','TransaccionesController');
Route::resource('egresos','EgresosController');
Route::resource('appStatus','AppStatusController');

