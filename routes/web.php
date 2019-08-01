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
    //Página de inicio
    Route::get('', 'InicioController@index')->name('inicio_administrador');
    //Enroutamiento para CRUD Decisiones
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_administrador');
        Route::get('crear-desicion', 'DecisionesController@crear')->name('crear_decision_administrador');
        Route::post('crear-decision', 'DecisionesController@guardar')->name('guardar_decision_administrador');
        Route::get('{id}/editar', 'DecisionesController@editar')->name('editar_decision_administrador');
        Route::put('{id}', 'DecisionesController@actualizar')->name('actualizar_decision_administrador');
        Route::delete('{id}', 'DecisionesController@eliminar')->name('eliminar_decision_administrador');
    });
    //Enroutamiento para CRUD Director de Proyectos
    Route::group(['prefix' => 'director-proyectos'], function () {
        Route::get('', 'DirectorProyectosController@index')->name('directores_administrador');
        Route::get('crear-director', 'DirectorProyectosController@crear')->name('crear_director_administrador');
        Route::post('crear-director', 'DirectorProyectosController@guardar')->name('guardar_director_administrador');
        Route::get('{id}/editar', 'DirectorProyectosController@editar')->name('editar_director_administrador');
        Route::put('{id}', 'DirectorProyectosController@actualizar')->name('actualizar_director_administrador');
        Route::delete('{id}', 'DirectorProyectosController@eliminar')->name('eliminar_director_administrador');
    });
    //Enroutamiento para CRUD Roles
    Route::group(['prefix' => 'roles'], function () {
        Route::get('', 'RolesController@index')->name('roles_administrador');
        Route::get('crear-rol', 'RolesController@crear')->name('crear_rol_administrador');
        Route::post('crear-rol', 'RolesController@guardar')->name('guardar_rol_administrador');
        Route::get('{id}/editar', 'RolesController@editar')->name('editar_rol_administrador');
        Route::put('{id}', 'RolesController@actualizar')->name('actualizar_rol_administrador');
        Route::delete('{id}', 'RolesController@eliminar')->name('eliminar_rol_administrador');
    });
    //Enroutamiento para Sistema de Permisos
    Route::group(['prefix' => 'permisos'], function () {
        Route::get('', 'PermisosController@index')->name('permisos_administrador');
        Route::get('crear-menu', 'PermisosController@crear')->name('crear_menu_administrador');
        Route::post('crear-menu', 'PermisosController@guardar')->name('guardar_menu_administrador');
        Route::post('asignar-permiso', 'PermisosController@asignarPermiso')->name('asignar_permiso_administrador');
        Route::get('{id}/editar', 'PermisosController@editar')->name('editar_permiso_administrador');
        Route::put('{id}', 'PermisosController@actualizar')->name('actualizar_permiso_administrador');
        Route::delete('{id}', 'PermisosController@eliminar')->name('eliminar_permiso_administrador');
    });
});

//Enrutamiento Cliente
Route::group(['prefix' => 'cliente', 'namespace' => 'Cliente', 'middleware' => ['auth', 'cliente']], function () {
    Route::get('', 'InicioController@index')->name('inicio_cliente');
});

//Enrutamiento Director de Proyectos
Route::group(['prefix' => 'director', 'namespace' => 'Director', 'middleware' => ['auth', 'director']], function () {
    Route::get('', 'InicioController@index')->name('inicio_director');

    //Enrutamiento CRUD Roles
    Route::Group(['prefix' => 'roles'], function () {
        Route::get('', 'RolesController@index')->name('roles_director');
        Route::get('crear', 'RolesController@crear')->name('crear_rol_director');
        Route::post('crear', 'RolesController@guardar')->name('guardar_rol_director');
        Route::get('{id}/editar', 'RolesController@editar')->name('editar_rol_director');
        Route::put('{id}', 'RolesController@actualizar')->name('actualizar_rol_director');
        Route::delete('{id}', 'RolesController@eliminar')->name('eliminar_rol_director');
    });

    //Enrutamiento CRUD Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('{idP}', 'ActividadesController@index')->name('actividades_director');
        Route::get('{idP}/crear', 'ActividadesController@crear')->name('crear_actividad_director');
        Route::post('crear', 'ActividadesController@guardar')->name('guardar_actividad_director');
        Route::get('{idP}-{idR}/editar', 'ActividadesController@editar')->name('editar_actividad_director');
        Route::put('{idP}-{idR}', 'ActividadesController@actualizar')->name('actualizar_actividad_director');
        Route::delete('{idP}-{idR}', 'ActividadesController@eliminar')->name('eliminar_actividad_director');
    });

    //Erutamiento CRUD Decisiones
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_director');
        Route::get('crear-desicion', 'DecisionesController@crear')->name('crear_decision_director');
        Route::post('crear-decision', 'DecisionesController@guardar')->name('guardar_decision_director');
        Route::get('{id}/editar', 'DecisionesController@editar')->name('editar_decision_director');
        Route::put('{id}', 'DecisionesController@actualizar')->name('actualizar_decision_director');
        Route::delete('{id}', 'DecisionesController@eliminar')->name('eliminar_decision_director');
    });

    //Erutamiento CRUD Perfil de Operación
    Route::group(['prefix' => 'perfil-operacion'], function () {
        Route::get('', 'PerfilOperacionController@index')->name('perfil_director');
        Route::get('crear', 'PerfilOperacionController@crear')->name('crear_perfil_director');
        Route::post('crear', 'PerfilOperacionController@guardar')->name('guardar_perfil_director');
        Route::get('{id}/editar', 'PerfilOperacionController@editar')->name('editar_perfil_director');
        Route::put('{id}', 'PerfilOperacionController@actualizar')->name('actualizar_perfil_director');
        Route::delete('{id}', 'PerfilOperacionController@eliminar')->name('eliminar_perfil_director');
    });

    //Erutamiento CRUD Requerimientos
    Route::group(['prefix' => 'requerimientos'], function () {
        Route::get('{idP}', 'RequerimientosController@index')->name('requerimientos_director');
        Route::get('{idP}/crear', 'RequerimientosController@crear')->name('crear_requerimiento_director');
        Route::post('crear', 'RequerimientosController@guardar')->name('guardar_requerimiento_director');
        Route::get('{idP}-{idR}/editar', 'RequerimientosController@editar')->name('editar_requerimiento_director');
        Route::put('{idP}-{idR}', 'RequerimientosController@actualizar')->name('actualizar_requerimiento_director');
        Route::delete('{idP}-{idR}', 'RequerimientosController@eliminar')->name('eliminar_requerimiento_director');
    });

    //Erutamiento CRUD Clientes
    Route::group(['prefix' => 'clientes'], function () {
        Route::get('', 'ClientesController@index')->name('clientes_director');
        Route::get('crear', 'ClientesController@crear')->name('crear_cliente_director');
        Route::post('crear', 'ClientesController@guardar')->name('guardar_cliente_director');
        Route::get('{id}/editar', 'ClientesController@editar')->name('editar_cliente_director');
        Route::put('{id}', 'ClientesController@actualizar')->name('actualizar_cliente_director');
        Route::delete('{id}', 'ClientesController@eliminar')->name('eliminar_cliente_director');
    });
    
    //Enrutamiento CRUD Proyectos
    Route::Group(['prefix' => 'proyectos'], function () {
        Route::get('', 'ProyectosController@index')->name('proyectos_director');
        Route::get('crear', 'ProyectosController@crear')->name('crear_proyecto_director');
        Route::post('crear', 'ProyectosController@guardar')->name('guardar_proyecto_director');
    });

    //Enrutamiento Cobros
    Route::Group(['prefix' => 'cobros'], function () {
        Route::get('', 'CobrosController@index')->name('cobros_director');
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
    //Enrutamiento CRUD Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_perfil_operacion');
        Route::get('{id}/asignacion-horas', 'ActividadesController@asignarHoras')->name('actividades_asignar_horas_perfil_operacion');
        Route::post('asignacion-horas', 'ActividadesController@guardarHoras')->name('actividades_guardar_horas_perfil_operacion');
        Route::get('{id}/documento', 'ActividadesController@descargarDocumentoSoporte')->name('actividades_descargar_archivo_perfil_operacion');
        Route::get('generar-pdf', 'ActividadesController@generarPdf')->name('generar_pdf_perfil_operacion');
        Route::get('{id}/finalizar', 'ActividadesController@finalizar')->name('actividades_finalizar_perfil_operacion');
        Route::post('finalizar', 'ActividadesController@guardarFinalizar')->name('actividades_guardar_finalizar_perfil_operacion');
    });
});

//Enrutamiento Tester
Route::group(['prefix' => 'tester', 'namespace' => 'Tester', 'middleware' => ['auth', 'tester']], function () {
    Route::get('', 'InicioController@index')->name('inicio_tester');
    Route::get('{id}/aprobacion', 'InicioController@aprobacionActividad')->name('aprobar_actividad_tester');
    Route::get('{ruta}/descargar', 'InicioController@descargarArchivo')->name('descargar_documento_actividad_tester');
    Route::get('{id}/respuestaR', 'InicioController@respuestaRechazado')->name('respuestaR_tester');
    Route::get('{id}/respuestaA', 'InicioController@respuestaAprobado')->name('respuestaA_tester');
});