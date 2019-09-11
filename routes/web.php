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
    //Enroutamiento para CRUD Decisiones
    Route::group(['prefix' => 'decisiones'], function () {
        Route::get('', 'DecisionesController@index')->name('decisiones_administrador');
        Route::get('crear-desicion', 'DecisionesController@crear')->name('crear_decision_administrador');
        Route::get('{id}/total-indicador', 'DecisionesController@totalIndicador')->name('total_indicador_administrador');
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
    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_administrador');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_administrador');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_administrador');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_administrador');
    });
    //Enroutamiento para Editar Datos Empresa
    Route::group(['prefix' => 'empresa'], function () {
        Route::get('', 'EmpresaController@index')->name('empresa_administrador');
        Route::put('editar', 'EmpresaController@actualizarDatos')->name('actualizar_empresa_administrador');
        Route::post('foto', 'EmpresaController@actualizarLogo')->name('actualizar_logo_empresa_administrador');
    });
    //Enroutamiento para Sistema de Asignación de Roles
    Route::group(['prefix' => 'asignar-rol'], function () {
        Route::get('', 'PermisosController@index')->name('asignar_rol_administrador');
        //Route::post('crear-menu', 'PermisosController@guardar')->name('guardar_menu_administrador');
        Route::get('{id}', 'PermisosController@asignarRol')->name('asignar_rol_usuario_administrador');
        Route::get('{idU}-{idR}/agregar', 'PermisosController@agregar')->name('agregar_rol_administrador');
        Route::get('{idU}-{idR}/quitar', 'PermisosController@quitar')->name('quitar_rol_administrador');
        //Route::put('{id}', 'PermisosController@actualizar')->name('actualizar_permiso_administrador');
        //Route::delete('{id}', 'PermisosController@eliminar')->name('eliminar_permiso_administrador');
    });
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

    //Enrutamiento CRUD Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('{idP}', 'ActividadesController@index')->name('actividades_director');
        Route::get('{idP}/crear', 'ActividadesController@crear')->name('crear_actividad_director');
        Route::post('crear', 'ActividadesController@guardar')->name('guardar_actividad_director');
        Route::get('{idP}-{idR}/editar', 'ActividadesController@editar')->name('editar_actividad_director');
        Route::put('{idP}-{idR}', 'ActividadesController@actualizar')->name('actualizar_actividad_director');
        Route::delete('{idP}-{idR}', 'ActividadesController@eliminar')->name('eliminar_actividad_director');
        Route::get('{idH}/aprobar', 'ActividadesController@aprobarHoras')->name('aprobar_horas_actividad_director');
        Route::put('{idH}/aprobar', 'ActividadesController@actualizarHoras')->name('actualizar_horas_actividad_director');
        Route::get('{idA}/terminar-aprobacion', 'ActividadesController@finalizarAprobacion')->name('finalizar_horas_actividad_director');
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

    //Erutamiento CRUD Requerimientos
    Route::group(['prefix' => 'requerimientos'], function () {
        Route::get('{idP}', 'RequerimientosController@index')->name('requerimientos_director');
        Route::get('{idP}/crear', 'RequerimientosController@crear')->name('crear_requerimiento_director');
        Route::post('crear', 'RequerimientosController@guardar')->name('guardar_requerimiento_director');
        Route::get('{idP}-{idR}/editar', 'RequerimientosController@editar')->name('editar_requerimiento_director');
        Route::put('{idR}/editar', 'RequerimientosController@actualizar')->name('actualizar_requerimiento_director');
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
        Route::get('{id}/generar-pdf', 'ProyectosController@generarPdf')->name('generar_pdf_proyecto_director');
        Route::get('{id}', 'ProyectosController@obtenerPorcentaje')->name('obtener_porcentaje_director');
    });

    //Enrutamiento Cobros
    Route::Group(['prefix' => 'cobros'], function () {
        Route::get('', 'CobrosController@index')->name('cobros_director');
        Route::get('{idA}-{idC}/agregarFactura', 'CobrosController@agregarFactura')->name('agregar_factura_director');
        Route::get('{id}/factura', 'CobrosController@generarFactura')->name('generar_factura_director');
    });

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_director');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_director');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_director');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_director');
    });
});

// Enrutamiento Area de Finanzas
Route::group(['prefix' => 'finanzas', 'namespace' => 'Finanzas', 'middleware' => ['auth', 'finanzas']], function () {
    Route::get('', 'InicioController@index')->name('inicio_finanzas');
    Route::get('{id}', 'InicioController@agregarCosto')->name('agregar_costo_actividad_finanzas');
    Route::put('cobro', 'InicioController@actualizarCosto')->name('actualizar_costo_actividad_finanzas');
    Route::get('{id}/factura', 'InicioController@generarFactura')->name('generar_factura_finanzas');

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_finanzas');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_finanzas');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_finanzas');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_finanzas');
    });
});

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

    //Enroutamiento para Editar Perfil
    Route::group(['prefix' => 'perfil'], function () {
        Route::get('', 'PerfilUsuarioController@index')->name('perfil_perfil_operacion');
        Route::put('editar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil_perfil_operacion');
        Route::put('clave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_perfil_operacion');
        Route::post('foto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto_perfil_operacion');
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