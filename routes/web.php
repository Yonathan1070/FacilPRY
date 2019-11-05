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
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_administrador');
    //Enroutamiento para CRUD Director de Proyectos
    Route::group(['prefix' => 'director-proyectos'], function () {
        Route::get('', 'DirectorProyectosController@index')->name('directores_administrador');
        Route::get('crear-director', 'DirectorProyectosController@crear')->name('crear_director_administrador');
        Route::post('crear-director', 'DirectorProyectosController@guardar')->name('guardar_director_administrador');
        Route::get('{id}/editar', 'DirectorProyectosController@editar')->name('editar_director_administrador');
        Route::put('{id}', 'DirectorProyectosController@actualizar')->name('actualizar_director_administrador');
        Route::delete('{id}', 'DirectorProyectosController@eliminar')->name('eliminar_director_administrador');
    });
    //Enroutamiento para Sistema de Permisos 
    Route::group(['prefix' => 'asignar-permiso'], function () {
        Route::get('', 'PermisosController@index')->name('asignar_rol_administrador');
        Route::get('{id}', 'PermisosController@asignarMenu')->name('asignar_menu_usuario_administrador');
        Route::get('{id}-{menuId}/agregar', 'PermisosController@agregar')->name('agregar_rol_administrador');
        Route::get('{id}-{menuId}/quitar', 'PermisosController@quitar')->name('quitar_rol_administrador');
        Route::get('{id}-{menuId}/agregarPermiso', 'PermisosController@agregarPermiso')->name('agregar_permiso_administrador');
        Route::get('{id}-{menuId}/quitarPermiso', 'PermisosController@quitarPermiso')->name('quitar_permiso_administrador');
    });
    //Enroutamiento para Crear de Permisos
    Route::group(['prefix' => 'permisos'], function () {
        Route::get('', 'PermisosController@crear')->name('crear_permiso_administrador');
        Route::post('crear-permiso', 'PermisosController@guardar')->name('guardar_permiso_administrador');
    });
    //Enroutamiento para CRUD Decisiones
    Route::group(['prefix' => 'menu'], function () {
        Route::get('', 'MenuController@inicio')->name('menu');
        Route::get('crear', 'MenuController@crear')->name('crear_menu');
        Route::post('crear', 'MenuController@guardar')->name('guardar_menu');
        Route::post('guardar-orden', 'MenuController@guardarOrden')->name('guardar_orden');
        Route::get('{id}/editar', 'MenuController@editar')->name('editar_menu');
        Route::put('{id}', 'MenuController@actualizar')->name('actualizar_menu');
        Route::get('{id}', 'MenuController@eliminar')->name('eliminar_menu');
    });
    //Enroutamiento para Editar Datos Empresa
    Route::group(['prefix' => 'empresa'], function () {
        Route::get('', 'EmpresaController@index')->name('empresa_administrador');
        Route::put('editar', 'EmpresaController@actualizarDatos')->name('actualizar_empresa_administrador');
        Route::post('foto', 'EmpresaController@actualizarLogo')->name('actualizar_logo_empresa_administrador');
    });
});

//Enrutamiento Director de Proyectos
Route::group(['prefix' => 'director', 'namespace' => 'Director', 'middleware' => ['auth', 'director']], function () {
    Route::get('', 'InicioController@index')->name('inicio_director');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_director');

    //Enrutamiento CRUD Roles
    Route::Group(['prefix' => 'roles'], function () {
        Route::get('', 'RolesController@index')->name('roles_director');
        Route::get('crear', 'RolesController@crear')->name('crear_rol_director');
        Route::post('crear', 'RolesController@guardar')->name('guardar_rol_director');
        Route::get('{id}/editar', 'RolesController@editar')->name('editar_rol_director');
        Route::put('{id}', 'RolesController@actualizar')->name('actualizar_rol_director');
        Route::delete('{id}', 'RolesController@eliminar')->name('eliminar_rol_director');
    });

    //Erutamiento CRUD Decisiones
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_director');
        Route::get('crear-desicion', 'DecisionesController@crear')->name('crear_decision_director');
        Route::post('crear-decision', 'DecisionesController@guardar')->name('guardar_decision_director');
        Route::get('{id}/editar', 'DecisionesController@editar')->name('editar_decision_director');
        Route::put('{id}', 'DecisionesController@actualizar')->name('actualizar_decision_director');
        Route::delete('{id}', 'DecisionesController@eliminar')->name('eliminar_decision_director');
        Route::get('{id}/total-indicador', 'DecisionesController@totalIndicador')->name('total_indicador_director');
    });

    //Erutamiento CRUD Perfil de Operación
    Route::group(['prefix' => 'perfil-operacion'], function () {
        Route::get('', 'PerfilOperacionController@index')->name('perfil_operacion_director');
        Route::get('crear', 'PerfilOperacionController@crear')->name('crear_perfil_director');
        Route::post('crear', 'PerfilOperacionController@guardar')->name('guardar_perfil_director');
        Route::get('{id}/editar', 'PerfilOperacionController@editar')->name('editar_perfil_director');
        Route::put('{id}', 'PerfilOperacionController@actualizar')->name('actualizar_perfil_operacion_director');
        Route::delete('{id}', 'PerfilOperacionController@eliminar')->name('eliminar_perfil_director');
    });

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_director');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_director');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_director');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_director');
    });
});

//Enrutamiento Perfil de Operacion
Route::group(['prefix' => 'perfil-operacion', 'namespace' => 'PerfilOperacion', 'middleware' => ['auth', 'perfiloperacion']], function () {
    Route::get('', 'InicioController@index')->name('inicio_perfil_operacion');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_perfil_operacion');
    //Enrutamiento CRUD Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_perfil_operacion');
        Route::get('{id}/asignacion-horas', 'ActividadesController@asignarHoras')->name('actividades_asignar_horas_perfil_operacion');
        Route::get('{id}/terminar-asignacion', 'ActividadesController@terminarAsignacion')->name('terminar_asignar_horas_perfil_operacion');
        Route::put('{id}/asignacion-horas', 'ActividadesController@guardarHoras')->name('actividades_guardar_horas_perfil_operacion');
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
    Route::post('respuestaR', 'InicioController@respuestaRechazado')->name('respuestaR_tester');
    Route::post('respuestaA', 'InicioController@respuestaAprobado')->name('respuestaA_tester');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_tester');

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_tester');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_tester');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_tester');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_tester');
    });
});

// Enrutamiento Area de Finanzas
Route::group(['prefix' => 'finanzas', 'namespace' => 'Finanzas', 'middleware' => ['auth', 'finanzas']], function () {
    Route::get('', 'InicioController@index')->name('inicio_finanzas');
    Route::get('{id}', 'InicioController@agregarCosto')->name('agregar_costo_actividad_finanzas');
    Route::put('cobro', 'InicioController@actualizarCosto')->name('actualizar_costo_actividad_finanzas');
    Route::get('{id}/factura', 'InicioController@generarFactura')->name('generar_factura_finanzas');
});

//Enrutamiento Cliente
Route::group(['prefix' => 'cliente', 'namespace' => 'Cliente', 'middleware' => ['auth', 'cliente']], function () {
    Route::get('', 'InicioController@index')->name('inicio_cliente');
    Route::get('{id}/generar-pdf', 'InicioController@generarPdf')->name('generar_pdf_proyecto_cliente');
    Route::get('{id}/factura', 'InicioController@generarFactura')->name('generar_factura_cliente');
    Route::get('{id}/pagar', 'InicioController@pagar')->name('pagar_factura_cliente');
    Route::get('{id}/info-pago', 'InicioController@informacionPago')->name('informacion_pago_cliente');
    Route::get('respuesta-pago', 'InicioController@respuestaPago')->name('respuesta_pago_cliente');
    Route::get('confirmacion-pago', 'InicioController@confirmacionPago')->name('confirmacion_pago_cliente');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_cliente');
    
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_cliente');
        Route::get('{id}/aprobar', 'ActividadesController@aprobarActividad')->name('aprobar_actividad_cliente');
        Route::get('{ruta}/descargar', 'ActividadesController@descargarArchivo')->name('descargar_documento_actividad_cliente');
        Route::post('respuestaR', 'ActividadesController@respuestaRechazado')->name('respuestaR_cliente');
        Route::post('respuestaA', 'ActividadesController@respuestaAprobado')->name('respuestaA_cliente');
    });

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_cliente');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_cliente');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_cliente');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_cliente');
    });
});

//Rutas Decisiones
Route::get('decisiones', 'DecisionesController@index')->name('decisiones');
Route::get('decisiones/crear-desicion', 'DecisionesController@crear')->name('crear_decision');
Route::get('decisiones/{id}/total-indicador', 'DecisionesController@totalIndicador')->name('total_indicador');
Route::post('decisiones/crear-decision', 'DecisionesController@guardar')->name('guardar_decision');
Route::get('decisiones/{id}/editar', 'DecisionesController@editar')->name('editar_decision');
Route::put('decisiones/{id}', 'DecisionesController@actualizar')->name('actualizar_decision');
Route::delete('decisiones/{id}', 'DecisionesController@eliminar')->name('eliminar_decision');

//Rutas Roles
Route::get('roles', 'RolesController@index')->name('roles');
Route::get('roles/crear-rol', 'RolesController@crear')->name('crear_rol');
Route::post('roles/crear-rol', 'RolesController@guardar')->name('guardar_rol');
Route::get('roles/{id}/editar', 'RolesController@editar')->name('editar_rol');
Route::put('roles/{id}', 'RolesController@actualizar')->name('actualizar_rol');
Route::delete('roles/{id}', 'RolesController@eliminar')->name('eliminar_rol');

//Rutas Perfil de Usuario
Route::get('perfil', 'PerfilUsuarioController@index')->name('perfil');
Route::put('perfileditar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil');
Route::put('perfilclave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave');
Route::post('perfilfoto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto');

//Rutas CRUD Clientes
Route::get('clientes', 'ClientesController@index')->name('clientes');
Route::get('clientes/crear', 'ClientesController@crear')->name('crear_cliente');
Route::post('clientes/crear', 'ClientesController@guardar')->name('guardar_cliente');
Route::get('clientes/{id}/editar', 'ClientesController@editar')->name('editar_cliente');
Route::put('clientes/{id}', 'ClientesController@actualizar')->name('actualizar_cliente');
Route::delete('{id}', 'ClientesController@eliminar')->name('eliminar_cliente');

//Rutas CRUD Proyectos
Route::get('proyectos', 'ProyectosController@index')->name('proyectos');
Route::get('proyectos/crear', 'ProyectosController@crear')->name('crear_proyecto');
Route::post('proyectos/crear', 'ProyectosController@guardar')->name('guardar_proyecto');
Route::get('proyectos/{id}/generar-pdf', 'ProyectosController@generarPdf')->name('generar_pdf_proyecto');
Route::get('proyectos/{id}', 'ProyectosController@obtenerPorcentaje')->name('obtener_porcentaje');

//Rutas CRUD Requerimientos
Route::get('requerimientos/{idP}', 'RequerimientosController@index')->name('requerimientos');
Route::get('requerimientos/{idP}/crear', 'RequerimientosController@crear')->name('crear_requerimiento');
Route::post('requerimientos/crear', 'RequerimientosController@guardar')->name('guardar_requerimiento');
Route::get('requerimientos/{idP}-{idR}/editar', 'RequerimientosController@editar')->name('editar_requerimiento');
Route::put('requerimientos/{idR}/editar', 'RequerimientosController@actualizar')->name('actualizar_requerimiento');
Route::delete('requerimientos/{idP}-{idR}', 'RequerimientosController@eliminar')->name('eliminar_requerimiento');

//Rutas CRUD Actividades
Route::get('actividades/{idP}', 'ActividadesController@index')->name('actividades');
Route::get('actividades/{idP}/crear', 'ActividadesController@crear')->name('crear_actividad');
Route::post('actividades/crear', 'ActividadesController@guardar')->name('guardar_actividad');
Route::get('actividades/{idP}-{idR}/editar', 'ActividadesController@editar')->name('editar_actividad');
Route::put('actividades/{idP}-{idR}', 'ActividadesController@actualizar')->name('actualizar_actividad');
Route::delete('actividades/{idP}-{idR}', 'ActividadesController@eliminar')->name('eliminar_actividad');
Route::get('actividades/{idH}/aprobar', 'ActividadesController@aprobarHoras')->name('aprobar_horas_actividad');
Route::put('actividades/{idH}/aprobar', 'ActividadesController@actualizarHoras')->name('actualizar_horas_actividad');
Route::get('actividades/{idA}/terminar-aprobacion', 'ActividadesController@finalizarAprobacion')->name('finalizar_horas_actividad');

//Rutas Cobros
Route::get('cobros', 'CobrosController@index')->name('cobros');
Route::get('cobros/{idA}-{idC}/agregarFactura', 'CobrosController@agregarFactura')->name('agregar_factura');
Route::get('cobros/{id}/factura', 'CobrosController@generarFactura')->name('generar_factura');

//Enrutamiento Usuario General
Route::group(['prefix' => '/', 'namespace' => 'General'], function () {
    Route::get('', 'InicioController@index')->name('inicio');
    Route::get('iniciar-sesion', 'LoginController@index')->name('login');
    Route::post('iniciar-sesion', 'LoginController@login')->name('login_post');
    Route::get('cerrar-sesion', 'LoginController@logout')->name('logout');
    Route::get('recuperar-clave', 'RecuperarClaveController@showLinkRequestForm')->name('recuperar_clave');
    Route::post('enviar-correo', 'RecuperarClaveController@sendResetLinkEmail')->name('enviar_correo');
    Route::get('actualizar-clave/{token}', 'RecuperarClaveController@cambiarClave')->name('cambiar_clave');
    Route::post('actualizar-clave', 'RecuperarClaveController@actualizarClave')->name('actualizar_clave');
});
