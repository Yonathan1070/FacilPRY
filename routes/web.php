<?php

use App\Http\Controllers\PerfilOperacion\ActividadesController;
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
    Route::get('{id}/cambio-estado-todo', 'InicioController@cambiarEstadoTodasNotificaciones')->name('cambiar_estado_todo_administrador');
    Route::get('{id}/limpiar-notificaciones', 'InicioController@limpiarNotificacion')->name('limpiar_administrador');
    Route::get('notificaciones', 'InicioController@verTodas')->name('notificaciones_administrador');
    Route::get('logs', 'LogsController@index')->name('logs_administrador');
    Route::get('logs-historico', 'LogsController@historico')->name('logs_historico_administrador');
    //Enroutamiento para CRUD Director de Proyectos
    Route::group(['prefix' => 'director-proyectos'], function () {
        Route::get('', 'DirectorProyectosController@index')->name('directores_administrador');
        Route::post('crear-director', 'DirectorProyectosController@guardar')->name('guardar_director_administrador');
        Route::get('{id}/editar', 'DirectorProyectosController@editar')->name('editar_director_administrador');
        Route::put('{id}', 'DirectorProyectosController@actualizar')->name('actualizar_director_administrador');
        Route::delete('{id}', 'DirectorProyectosController@eliminar')->name('eliminar_director_administrador');
        Route::get('{id}/inactivar', 'DirectorProyectosController@inactivar')->name('inactivar_director_administrador');
        Route::get('{id}/activar', 'DirectorProyectosController@activar')->name('activar_director_administrador');
        Route::put('{id}/restaurar_clave', 'DirectorProyectosController@recuperar_contraseña')->name('reset_pass_director_administrador');
    });
    //Enroutamiento para Sistema de Permisos 
    Route::group(['prefix' => 'asignar-permiso'], function () {
        Route::get('', 'PermisosController@index')->name('asignar_rol_administrador');
        Route::get('{id}', 'PermisosController@asignarMenu')->name('asignar_menu_usuario_administrador');
        Route::get('{id}-{menuId}/agregar', 'PermisosController@agregar')->name('agregar_rol_administrador');
        Route::get('{id}-{menuId}/quitar', 'PermisosController@quitar')->name('quitar_rol_administrador');
        Route::get('{id}-{menuId}/agregarPermiso', 'PermisosController@agregarPermiso')->name('agregar_permiso_administrador');
        Route::get('{id}-{menuId}/quitarPermiso', 'PermisosController@quitarPermiso')->name('quitar_permiso_administrador');
        Route::get('{id}-{rolId}/agregarRol', 'PermisosController@agregarRol')->name('agregar_rol_administrador');
        Route::get('{id}-{rolId}/quitarRol', 'PermisosController@quitarRol')->name('quitar_rol_administrador');
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
    Route::get('/met', 'InicioController@index')->name('met');
    Route::get('/metrica', 'InicioController@metrica')->name('metrica_director');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_director');
    Route::get('{id}/cambio-estado-todo', 'InicioController@cambiarEstadoTodasNotificaciones')->name('cambiar_estado_todo_director');
    Route::get('{id}/limpiar-notificaciones', 'InicioController@limpiarNotificacion')->name('limpiar_director');
    Route::get('notificaciones', 'InicioController@verTodas')->name('notificaciones_director');
});

//Enrutamiento Perfil de Operacion
Route::group(['prefix' => 'perfil-operacion', 'namespace' => 'PerfilOperacion', 'middleware' => ['auth', 'perfiloperacion']], function () {
    Route::get('', 'InicioController@index')->name('inicio_perfil_operacion');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_perfil_operacion');
    Route::get('{id}/cambio-estado-todo', 'InicioController@cambiarEstadoTodasNotificaciones')->name('cambiar_estado_todo_perfil_operacion');
    Route::get('{id}/limpiar-notificaciones', 'InicioController@limpiarNotificacion')->name('limpiar_perfil_operacion');
    Route::get('notificaciones', 'InicioController@verTodas')->name('notificaciones_perfil_operacion');
    //Enrutamiento Actividades
    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_perfil_operacion');
        Route::get('{id}/asignacion-horas', 'ActividadesController@asignarHoras')->name('actividades_asignar_horas_perfil_operacion');
        Route::get('{id}/terminar-asignacion', 'ActividadesController@terminarAsignacion')->name('terminar_asignar_horas_perfil_operacion');
        Route::put('{id}/asignacion-horas', 'ActividadesController@guardarHoras')->name('actividades_guardar_horas_perfil_operacion');
        Route::get('{id}/documento', 'ActividadesController@descargarDocumentoSoporte')->name('actividades_descargar_archivo_perfil_operacion');
        Route::get('generar-pdf', 'ActividadesController@generarPdf')->name('generar_pdf_perfil_operacion');
        Route::get('{id}/finalizar', 'ActividadesController@finalizar')->name('actividades_finalizar_perfil_operacion');
        Route::post('finalizar', 'ActividadesController@guardarFinalizar')->name('actividades_guardar_finalizar_perfil_operacion');
        Route::get('{id}', 'ActividadesController@solicitarTiempo')->name('actividades_solicitar_tiempo_perfil_operacion');
        Route::post('{id}/solicitud', 'ActividadesController@enviarSolicitud')->name('actividades_enviar_solicitud_tiempo_perfil_operacion');
    });
    //Enrutamiento Metricas
    Route::get('eficacia', 'MetricasController@metricaEficaciaGeneral')->name('eficacia_general_perfil_operacion');
    Route::get('eficiencia', 'MetricasController@metricaEficienciaGeneral')->name('eficiencia_general_perfil_operacion');
    Route::get('efectividad', 'MetricasController@metricaEfectividadGeneral')->name('efectividad_general_perfil_operacion');
    Route::get('eficacia-carga', 'MetricasController@metricaEficaciaCarga')->name('eficacia_carga_perfil_operacion');
    Route::get('eficiencia-carga', 'MetricasController@metricaEficienciaCarga')->name('eficiencia_carga_perfil_operacion');
    Route::get('efectividad-carga', 'MetricasController@metricaEfectividadCarga')->name('efectividad_carga_perfil_operacion');
    //Carga de trabajo
    Route::get('carga', 'InicioController@cargaTrabajo')->name('perfil_operacion_carga');
});

//Enrutamiento Cliente
Route::group(['prefix' => 'cliente', 'namespace' => 'Cliente', 'middleware' => ['auth', 'cliente']], function () {
    Route::get('', 'InicioController@index')->name('inicio_cliente');
    Route::get('{id}/generar-pdf', 'InicioController@generarPdf')->name('generar_pdf_proyecto_cliente');
    Route::get('{id}/factura', 'InicioController@generarFactura')->name('generar_factura_cliente');
    Route::get('{id}/factura-adicional', 'InicioController@generarFacturaAdicional')->name('generar_factura_adicional_cliente');
    Route::get('{id}/pagar', 'InicioController@pagar')->name('pagar_factura_cliente');
    Route::get('{id}/pagar-adicional', 'InicioController@pagarAdicional')->name('pagar_factura_adicional_cliente');
    Route::get('{id}/info-pago', 'InicioController@informacionPago')->name('informacion_pago_cliente');
    Route::get('{id}/info-pago-adicional', 'InicioController@informacionPagoAdicional')->name('informacion_pago_adicional_cliente');
    Route::get('respuesta-pago', 'InicioController@respuestaPago')->name('respuesta_pago_cliente');
    Route::get('confirmacion-pago', 'InicioController@confirmacionPago')->name('confirmacion_pago_cliente');
    Route::get('respuesta-pago-adicional', 'InicioController@respuestaPagoAdicional')->name('respuesta_pago_cliente_adicional');
    Route::get('confirmacion-pago-adicional', 'InicioController@confirmacionPagoAdicional')->name('confirmacion_pago_cliente_adicional');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_cliente');
    Route::get('{id}/cambio-estado-todo', 'InicioController@cambiarEstadoTodasNotificaciones')->name('cambiar_estado_todo_cliente');
    Route::get('{id}/limpiar-notificaciones', 'InicioController@limpiarNotificacion')->name('limpiar_cliente');
    Route::get('notificaciones', 'InicioController@verTodas')->name('notificaciones_cliente');

    Route::group(['prefix' => 'actividades'], function () {
        Route::get('', 'ActividadesController@index')->name('actividades_cliente');
        Route::get('{id}/finalizar', 'ActividadesController@finalizar')->name('actividades_finalizar_cliente');
        Route::post('finalizar', 'ActividadesController@guardarFinalizar')->name('actividades_guardar_finalizar_cliente');
        Route::get('{id}/detalle', 'ActividadesController@detalle')->name('detalle_actividad_cliente');
        Route::get('{id}/aprobar', 'ActividadesController@aprobarActividad')->name('aprobar_actividad_cliente');
        Route::get('{ruta}/descargar', 'ActividadesController@descargarArchivo')->name('descargar_documento_actividad_cliente');
        Route::post('respuestaR', 'ActividadesController@respuestaRechazado')->name('respuestaR_cliente');
        Route::post('respuestaA', 'ActividadesController@respuestaAprobado')->name('respuestaA_cliente');
    });
});

//Rutas Decisiones
Route::get('decisiones', 'DecisionesController@index')->name('decisiones');
Route::get('decisiones/crear-decision', 'DecisionesController@crear')->name('crear_decision');
Route::get('decisiones/{id}/total-indicador', 'DecisionesController@totalIndicador')->name('total_indicador');
Route::post('decisiones/crear-decision', 'DecisionesController@guardar')->name('guardar_decision');
Route::get('decisiones/{id}/editar', 'DecisionesController@editar')->name('editar_decision');
Route::put('decisiones/{id}', 'DecisionesController@actualizar')->name('actualizar_decision');
Route::delete('decisiones/{id}', 'DecisionesController@eliminar')->name('eliminar_decision');

//Ruta para calificar trabajadores
Route::get('calificacion', 'CalificarController@index')->name('calificacion_trabajadores');
Route::post('calificar', 'CalificarController@calificar')->name('calificar_trabajadores');
Route::get('calificaciones/{id}', 'CalificarController@obtener')->name('obtener_calificacion');

//Rutas Roles
Route::get('roles', 'RolesController@index')->name('roles');
Route::post('roles/crear-rol', 'RolesController@guardar')->name('guardar_rol');
Route::get('roles/{id}/editar', 'RolesController@editar')->name('editar_rol');
Route::put('roles/{id}', 'RolesController@actualizar')->name('actualizar_rol');
Route::delete('roles/{id}', 'RolesController@eliminar')->name('eliminar_rol');

//Rutas Perfil de Usuario
Route::get('perfil', 'PerfilUsuarioController@index')->name('perfil');
Route::put('perfileditar', 'PerfilUsuarioController@actualizarDatos')->name('actualizar_perfil');
Route::put('perfilclave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_perfil');
Route::post('perfilfoto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto');

//Rutas CRUD Empresas
Route::get('empresas', 'EmpresasController@index')->name('empresas');
Route::get('empresas/crear', 'EmpresasController@crear')->name('crear_empresa');
Route::post('empresas/crear', 'EmpresasController@guardar')->name('guardar_empresa');
Route::get('empresas/{id}/editar', 'EmpresasController@editar')->name('editar_empresa');
Route::put('empresas/{id}', 'EmpresasController@actualizar')->name('actualizar_empresa');
Route::put('empresa/{id}/inactivar', 'EmpresasController@inactivar')->name('inactivar_empresa');
Route::get('empresa/{id}/activar', 'EmpresasController@activar')->name('activar_empresa');

//Rutas CRUD Clientes
Route::get('clientes/{id}', 'ClientesController@index')->name('clientes');
Route::get('clientes/crear/{id}', 'ClientesController@crear')->name('crear_cliente');
Route::post('clientes/crear', 'ClientesController@guardar')->name('guardar_cliente');
Route::get('clientes/{idC}-{idE}/editar', 'ClientesController@editar')->name('editar_cliente');
Route::put('clientes/{idC}-{idE}', 'ClientesController@actualizar')->name('actualizar_cliente');
Route::delete('{id}', 'ClientesController@eliminar')->name('eliminar_cliente');
Route::put('clientes/{id}/restaurar_clave', 'ClientesController@recuperar_contraseña')->name('reset_pass_cliente');

//Rutas CRUD Perfil de Operación
Route::get('lperfil-operacion', 'PerfilOperacionController@index')->name('perfil_operacion');
Route::post('perfil-operacion/crear', 'PerfilOperacionController@guardar')->name('guardar_perfil_operacion');
Route::get('perfil-operacion/{id}/editar', 'PerfilOperacionController@editar')->name('editar_perfil_operacion');
Route::get('perfil-operacion/{id}/inactivar', 'PerfilOperacionController@inactivar')->name('inactivar_perfil_operacion');
Route::get('perfil-operacion/{id}/activar', 'PerfilOperacionController@activar')->name('activar_perfil_operacion');
Route::put('perfil-operacion/{id}', 'PerfilOperacionController@actualizar')->name('actualizar_perfil_operacion');
Route::delete('perfil-operacion/{id}', 'PerfilOperacionController@eliminar')->name('eliminar_perfil_operacion');
Route::get('perfil-operacion/{id}/agregar', 'PerfilOperacionController@agregar')->name('agregar_perfil_operacion');
Route::get('perfil-operacion/{id}/carga', 'PerfilOperacionController@cargaTrabajo')->name('carga_perfil_operacion');
Route::get('perfil-operacion/{id}/carga-pdf', 'PerfilOperacionController@pdfCargaTrabajo')->name('pdf_carga_perfil_operacion');
Route::put('perfil-operacion/{id}/restaurar_clave', 'PerfilOperacionController@recuperar_contraseña')->name('reset_pass_perfil_operacion');

//Rutas CRUD Proyectos
Route::get('lproyectos/{id}', 'ProyectosController@index')->name('proyectos');
Route::get('proyectos/crear/{id}', 'ProyectosController@crear')->name('crear_proyecto');
Route::post('proyectos/crear', 'ProyectosController@guardar')->name('guardar_proyecto');
Route::delete('proyectos/{idP}/eliminar', 'ProyectosController@eliminar')->name('eliminar_proyectos');
Route::get('proyectos/{id}/generar-pdf', 'ProyectosController@generarPdf')->name('generar_pdf_proyecto');
Route::get('proyectos/{id}', 'ProyectosController@obtenerPorcentaje')->name('obtener_porcentaje');
Route::get('proyectos/{id}/gantt', 'ProyectosController@gantt')->name('gantt');
Route::get('proyectos/{id}/gantt/descargar', 'ProyectosController@ganttDescargar')->name('gantt_descargar');
Route::get('proyectos/{id}/finalizar', 'ProyectosController@finalizar')->name('finalizar_proyecto');
Route::get('proyectos/{id}/activar', 'ProyectosController@activar')->name('activar_proyecto');

//Rutas CRUD Requerimientos
Route::get('requerimientos/{idP}', 'RequerimientosController@index')->name('requerimientos');
Route::get('requerimientos/{idP}/crear', 'RequerimientosController@crear')->name('crear_requerimiento');
Route::post('requerimientos/crear', 'RequerimientosController@guardar')->name('guardar_requerimiento');
Route::get('requerimientos/{idP}-{idR}/editar', 'RequerimientosController@editar')->name('editar_requerimiento');
Route::put('requerimientos/{idR}/editar', 'RequerimientosController@actualizar')->name('actualizar_requerimiento');
Route::delete('requerimientos/{idP}-{idR}', 'RequerimientosController@eliminar')->name('eliminar_requerimiento');
Route::get('prequerimientos/{id}', 'RequerimientosController@obtenerPorcentaje')->name('obtener_porcentaje_requerimiento');

//Rutas CRUD Actividades
Route::get('actividades/{idR}', 'ActividadesController@index')->name('actividades');
Route::get('actividades/{idP}/total', 'ActividadesController@todas')->name('actividades_todas');
Route::get('actividades/{idR}/crearT', 'ActividadesController@crearTrabajador')->name('crear_actividad_trabajador');
Route::get('actividades/{idR}/crearC', 'ActividadesController@crearCliente')->name('crear_actividad_cliente');
Route::post('actividades/crear/{idR}', 'ActividadesController@guardar')->name('guardar_actividad');
Route::get('actividades/{idA}/editarT', 'ActividadesController@editarTrabajador')->name('editar_actividad_trabajador');
Route::get('actividades/{idA}/editarC', 'ActividadesController@editarCliente')->name('editar_actividad_cliente');
Route::put('actividades/{idA}', 'ActividadesController@actualizar')->name('actualizar_actividad');
Route::delete('actividades/{idA}', 'ActividadesController@eliminar')->name('eliminar_actividad');
Route::put('actividades/{idA}/cambiar-requerimiento', 'ActividadesController@cambiarRequerimiento')->name('cambio_requerimiento_actividad');
Route::get('actividades/{idH}/aprobar', 'ActividadesController@aprobarHoras')->name('aprobar_horas_actividad');
Route::put('actividades/{idH}/aprobar', 'ActividadesController@actualizarHoras')->name('actualizar_horas_actividad');
Route::get('actividades/{idA}/terminar-aprobacion', 'ActividadesController@finalizarAprobacion')->name('finalizar_horas_actividad');
Route::get('actividades/{id}/detalle', 'ActividadesController@detalleActividadModal')->name('detalle_actividades');
Route::post('actividades/detalle-general', 'ActividadesController@detalleActividad')->name('detalle_general_actividad');
Route::get('actividades/{idA}/solicitud-tiempo', 'ActividadesController@solicitudTiempo')->name('solicitud_tiempo_actividades');
Route::get('actividades/{idS}/aprobar-solicitud', 'ActividadesController@aprobarSolicitud')->name('aprobar_solicitud_tiempo_actividades');

//Rutas Validador
Route::get('validador', 'ValidadorController@index')->name('inicio_validador');
Route::get('validador/{id}/verificar', 'ValidadorController@verificarActividad')->name('verificar_actividad_validador');
Route::get('validador/{id}/aprobacion', 'ValidadorController@aprobacionActividad')->name('aprobar_actividad_validador');
Route::get('validador/{ruta}/descargar', 'ValidadorController@descargarArchivo')->name('descargar_documento_actividad_validador');
Route::post('validador/respuestaR', 'ValidadorController@respuestaRechazado')->name('respuestaR_validador');
Route::post('validador/respuestaA', 'ValidadorController@respuestaAprobado')->name('respuestaA_validador');

//Rutas Cobros
Route::get('cobros', 'CobrosController@index')->name('cobros');
Route::get('cobros/{idA}-{idC}/agregarFactura', 'CobrosController@agregarFactura')->name('agregar_factura');
Route::get('cobros/{id}/factura', 'CobrosController@generarFactura')->name('generar_factura');

//Rutas Finanzas
Route::get('finanzas', 'FinanzasController@index')->name('inicio_finanzas');
Route::get('finanzas/{id}', 'FinanzasController@agregarCosto')->name('agregar_costo_actividad_finanzas');
Route::put('finanzas/cobro', 'FinanzasController@actualizarCosto')->name('actualizar_costo_actividad_finanzas');
Route::get('finanzas/{id}/factura', 'FinanzasController@generarFactura')->name('generar_factura_finanzas');
Route::get('agregar', 'FinanzasController@agregarCostosFactura')->name('agregar_cobro_finanzas');
Route::get('agregar/{id}/proyectos', 'FinanzasController@obtenerProyectos')->name('proyectos_cliente_finanzas');
Route::post('agregar/guardar', 'FinanzasController@guardarCostosFactura')->name('guardar_adicional');
Route::get('finanzas/{id}/factura-adicional', 'FinanzasController@generarFacturaAdicional')->name('generar_factura_adicional_finanzas');
Route::get('finanzas/{id}/editar-adicional', 'FinanzasController@editarCostosFactura')->name('editar_adicional_finanzas');
Route::put('finanzas/{id}/actualizar-adicional', 'FinanzasController@actualizarCostosFactura')->name('actualizar_adicional_finanzas');

//Rutas Metricas
Route::get('eficacia', 'MetricasController@metricaEficaciaGeneral')->name('eficacia_general');
Route::get('eficaciad', 'MetricasController@metricasGenerales')->name('metricas_generales');
Route::get('eficiencia', 'MetricasController@metricaEficienciaGeneral')->name('eficiencia_general');
Route::get('efectividad', 'MetricasController@metricaEfectividadGeneral')->name('efectividad_general');
Route::get('productividad', 'MetricasController@metricaProductividad')->name('productividad');
Route::get('barraseficacia', 'MetricasController@barrasEficaciaPorTrabajador')->name('eficacia_barras_trabajador');
Route::get('barraseficiencia', 'MetricasController@barrasEficienciaPorTrabajador')->name('eficiencia_barras_trabajador');
Route::get('barrasefectividad', 'MetricasController@barrasEfectividadPorTrabajador')->name('efectividad_barras_trabajador');
Route::get('eficacia-carga/{id}', 'MetricasController@metricaEficaciaCarga')->name('eficacia_carga_trabajador');
Route::get('eficiencia-carga/{id}', 'MetricasController@metricaEficienciaCarga')->name('eficiencia_carga_trabajador');
Route::get('efectividad-carga/{id}', 'MetricasController@metricaEfectividadCarga')->name('efectividad_carga_trabajador');

//Enrutamiento Usuario General
Route::group(['prefix' => '/', 'namespace' => 'General'], function () {
    Route::get('', 'LoginController@index')->name('inicio');
    Route::get('iniciar-sesion', 'LoginController@index')->name('login');
    Route::post('iniciar-sesion', 'LoginController@login')->name('login_post');
    Route::get('cerrar-sesion', 'LoginController@logout')->name('logout');
    Route::get('recuperar-clave', 'RecuperarClaveController@showLinkRequestForm')->name('recuperar_clave');
    Route::post('enviar-correo', 'RecuperarClaveController@sendResetLinkEmail')->name('enviar_correo');
    Route::get('actualizar-clave/{token}', 'RecuperarClaveController@cambiarClave')->name('cambiar_clave');
    Route::post('actualizar-clave', 'RecuperarClaveController@actualizarClave')->name('actualizar_clave');
    Route::post('elegir-rol', 'AjaxController@asignarSesion')->name('ajax');
});


//Rutas Modulo Parrilla
Route::get('parrilla', 'ParrillaController@index')->name('parrilla');
Route::get('parrilla/crear-parrilla', 'ParrillaController@create')->name('crear_parrilla');
Route::post('parrilla/crear-parrilla', 'ParrillaController@store')->name('guardar_parrilla');
Route::get('parrilla/{id}/editar', 'ParrillaController@edit')->name('editar_parrilla');
Route::put('parrilla/{id}', 'ParrillaController@update')->name('actualizar_parrilla');
Route::delete('parrilla/{id}', 'ParrillaController@delete')->name('eliminar_parrilla');
Route::get('parrilla/{id}/ver', 'ParrillaController@show')->name('ver_parrilla');

//Rutas Modulo Parrilla/publicaciones
Route::get('parrilla/publicacion/{id}', 'PublicacionesController@show')->name('publicacion');
Route::get('parrilla/publicacion/{id}/crear-publicacion', 'PublicacionesController@create')->name('crear_publicacion');
Route::post('parrilla/publicacion/crear-publicacion', 'PublicacionesController@store')->name('guardar_publicacion');
Route::get('parrilla/publicacion/{id}/ver-publicacion', 'PublicacionesController@ver')->name('ver_publicacion');
Route::get('parrilla/publicacion/{id}/editar', 'PublicacionesController@edit')->name('editar_publicacion');
Route::put('parrilla/publicacion/{id}', 'PublicacionesController@update')->name('actualizar_publicacion');
Route::delete('parrilla/publicacion/{id}', 'PublicacionesController@delete')->name('eliminar_publicacion');

//Rutas Modulo Parrilla/Piezas
Route::get('parrilla/pieza/{id}', 'PiezasController@show')->name('pieza');
Route::get('parrilla/pieza/{id}/crear-pieza', 'PiezasController@create')->name('crear_pieza');
Route::post('parrilla/pieza/crear-pieza', 'PiezasController@store')->name('guardar_pieza');
Route::get('parrilla/{id}/editar', 'PiezasController@edit')->name('editar_pieza');
Route::put('parrilla/{id}', 'PiezasController@update')->name('actualizar_pieza');
Route::delete('parrilla/{id}', 'PiezasController@delete')->name('eliminar_pieza');

//Rutas Modulo Comentarios
Route::post('parrilla/comentar', 'ComentariosController@store')->name('guardar_comentario');


