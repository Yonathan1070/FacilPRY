<?php
use Illuminate\Support\Facades\Route;

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

//Enrutamiento Administrador
Route::group(['prefix' => 'administrador', 'namespace' => 'Administrador', 'middleware' => ['auth', 'administrador']], function () {
    Route::get('', 'InicioController@index')->name('inicio_administrador');
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_administrador'); 
    });
    Route::group(['prefix' => 'director-proyectos'], function () {
        Route::get('', 'DirectorProyectosController@index')->name('director_administrador'); 
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('', 'RolesController@index')->name('roles_administrador'); 
    });
});

//Enrutamiento Cliente
Route::group(['prefix' => 'cliente', 'namespace' => 'Cliente', 'middleware' => ['auth', 'cliente']], function () {
    Route::get('', 'InicioController@index')->name('inicio_cliente');
});

//Enrutamiento Director de Proyectos
Route::group(['prefix' => 'director', 'namespace' => 'Director', 'middleware' => ['auth', 'director']], function () {
    Route::get('', 'InicioController@index')->name('inicio_director');

    //Enrutamiento CRUD Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_director');
    });

    //Erutamiento CRUD Decisiones
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_director');
    });

    //Erutamiento CRUD Perfil de Operación
    Route::group(['prefix' => 'perfil-operacion'], function () {
        Route::get('', 'PerfilOperacionController@index')->name('perfil_director');
    });

    //Erutamiento CRUD Requerimientos
    Route::group(['prefix' => 'requerimientos'], function () {
        Route::get('', 'RequerimientosController@index')->name('requerimientos_director');
    });

    //Enrutamiento CRUD Roles
    Route::Group(['prefix' => 'roles'], function () {
        Route::get('', 'RolesController@index')->name('roles_director');
        Route::get('crear', 'RolesController@crear')->name('crear_rol');
        Route::post('crear', 'RolesController@guardar')->name('guardar_rol');
        Route::get('{id}/editar', 'RolesController@editar')->name('editar_rol');
        Route::put('{id}', 'RolesController@actualizar')->name('actualizar_rol');
        Route::delete('{id}', 'RolesController@eliminar')->name('eliminar_rol');
    });
    
    //Enrutamiento CRUD Proyectos
    Route::Group(['prefix' => 'proyectos'], function () {
        Route::get('', 'ProyectosController@index')->name('proyectos_director');
        Route::get('crear', 'ProyectosController@crear')->name('crear_proyecto');
        Route::post('crear', 'ProyectosController@guardar')->name('guardar_proyecto');
        Route::get('{id}/editar', 'ProyectosController@editar')->name('editar_proyecto');
        Route::put('{id}', 'ProyectosController@actualizar')->name('actualizar_proyecto');
        Route::delete('{id}', 'ProyectosController@eliminar')->name('eliminar_proyecto');
    });
});

// Enrutamiento Area de Finanzas
Route::group(['prefix' => 'finanzas', 'namespace' => 'Finanzas', 'middleware' => ['auth', 'finanzas']], function () {
    Route::get('', 'InicioController@index')->name('inicio_finanzas');
});

//Enrutamiento Usuario General
Route::group(['prefix' => '/', 'namespace' => 'General'], function () {
    Route::get('', 'InicioController@index')->name('inicio');
    Route::get('iniciar-sesion', 'LoginController@index')->name('login');
    Route::post('iniciar-sesion', 'LoginController@login')->name('login_post');
    Route::get('cerrar-sesion', 'LoginController@logout')->name('logout');
});

//Enrutamiento Perfil de Operacion
Route::group(['prefix' => 'perfil-operacion', 'namespace' => 'PerfilOperacion', 'middleware' => ['auth', 'perfiloperacion']], function () {
    Route::get('', 'InicioController@index')->name('inicio_perfil_operacion');
});

//Enrutamiento Tester
Route::group(['prefix' => 'tester', 'namespace' => 'Tester', 'middleware' => ['auth', 'tester']], function () {
    Route::get('', 'InicioController@index')->name('inicio_tester');
});