<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Enrutamiento Usuario General
Route::group(['prefix' => '/', 'namespace' => 'General'], function () {
    Route::get('', 'InicioController@index')->name('inicio');
    Route::get('iniciar-sesion', 'LoginController@index')->name('iniciar_sesion');
});

//Enrutamiento Administrador
Route::group(['prefix' => 'administrador', 'namespace' => 'Administrador'], function () {
    Route::get('', 'InicioController@index')->name('inicio_administrador');
    Route::get('director-proyectos', 'DirectorProyectosController@index')->name('director_administrador');
});

//Enrutamiento Director de Proyectos
Route::group(['prefix' => 'director', 'namespace' => 'Director'], function () {
    Route::get('', 'InicioController@index')->name('inicio_director');
    Route::get('roles', 'RolesController@index')->name('roles');
    Route::get('roles-crear', 'RolesController@crear')->name('crear_rol');
    Route::post('roles-crear', 'RolesController@guardar')->name('guardar_rol');
    Route::get('rol-{id}/editar', 'RolesController@editar')->name('editar_rol');
    Route::put('rol-{id}', 'RolesController@actualizar')->name('actualizar_rol');
    Route::delete('rol-{id}', 'RolesController@eliminar')->name('eliminar_rol');
});
