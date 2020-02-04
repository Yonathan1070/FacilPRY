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
    Route::get('/met', 'InicioController@index')->name('met');
    Route::get('/metrica', 'InicioController@metrica')->name('metrica_director');
    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_director');
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
        Route::get('{id}', 'ActividadesController@solicitarTiempo')->name('actividades_solicitar_tiempo_perfil_operacion');
    });
    //Enrutamiento Metricas
    Route::get('eficacia', 'MetricasController@metricaEficaciaGeneral')->name('eficacia_general_perfil_operacion');
    Route::get('eficiencia', 'MetricasController@metricaEficienciaGeneral')->name('eficiencia_general_perfil_operacion');
    Route::get('efectividad', 'MetricasController@metricaEfectividadGeneral')->name('efectividad_general_perfil_operacion');
});

//Enrutamiento Tester
Route::group(['prefix' => 'tester', 'namespace' => 'Tester', 'middleware' => ['auth', 'tester']], function () {

    Route::get('{id}/cambio-estado', 'InicioController@cambiarEstadoNotificacion')->name('cambiar_estado_tester');
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
Route::put('perfilclave', 'PerfilUsuarioController@actualizarClave')->name('actualizar_clave_perfil');
Route::post('perfilfoto', 'PerfilUsuarioController@actualizarFoto')->name('actualizar_foto');

//Rutas CRUD Empresas
Route::get('empresas', 'EmpresasController@index')->name('empresas');
Route::get('empresas/crear', 'EmpresasController@crear')->name('crear_empresa');
Route::post('empresas/crear', 'EmpresasController@guardar')->name('guardar_empresa');
Route::get('empresas/{id}/editar', 'EmpresasController@editar')->name('editar_empresa');
Route::put('empresas/{id}', 'EmpresasController@actualizar')->name('actualizar_empresa');
Route::put('empresa/{id}', 'EmpresasController@inactivar')->name('inactivar_empresa');

//Rutas CRUD Clientes
Route::get('clientes/{id}', 'ClientesController@index')->name('clientes');
Route::get('clientes/crear/{id}', 'ClientesController@crear')->name('crear_cliente');
Route::post('clientes/crear', 'ClientesController@guardar')->name('guardar_cliente');
Route::get('clientes/{idC}-{idE}/editar', 'ClientesController@editar')->name('editar_cliente');
Route::put('clientes/{idC}-{idE}', 'ClientesController@actualizar')->name('actualizar_cliente');
Route::delete('{id}', 'ClientesController@eliminar')->name('eliminar_cliente');

//Rutas CRUD Perfil de Operación
Route::get('lperfil-operacion', 'PerfilOperacionController@index')->name('perfil_operacion');
Route::get('perfil-operacion/crear', 'PerfilOperacionController@crear')->name('crear_perfil_operacion');
Route::post('perfil-operacion/crear', 'PerfilOperacionController@guardar')->name('guardar_perfil_operacion');
Route::get('perfil-operacion/{id}/editar', 'PerfilOperacionController@editar')->name('editar_perfil_operacion');
Route::put('perfil-operacion/{id}', 'PerfilOperacionController@actualizar')->name('actualizar_perfil_operacion');
Route::delete('perfil-operacion/{id}', 'PerfilOperacionController@eliminar')->name('eliminar_perfil_operacion');
Route::get('perfil-operacion/{id}/agregar', 'PerfilOperacionController@agregar')->name('agregar_perfil_operacion');

//Rutas CRUD Proyectos
Route::get('lproyectos/{id}', 'ProyectosController@index')->name('proyectos');
Route::get('proyectos/crear/{id}', 'ProyectosController@crear')->name('crear_proyecto');
Route::post('proyectos/crear', 'ProyectosController@guardar')->name('guardar_proyecto');
Route::get('proyectos/{id}/generar-pdf', 'ProyectosController@generarPdf')->name('generar_pdf_proyecto');
Route::get('proyectos/{id}', 'ProyectosController@obtenerPorcentaje')->name('obtener_porcentaje');
Route::get('proyectos/gantt/gantt', 'ProyectosController@gantt')->name('gantt');

//Rutas CRUD Requerimientos
Route::get('requerimientos/{idP}', 'RequerimientosController@index')->name('requerimientos');
Route::get('requerimientos/{idP}/crear', 'RequerimientosController@crear')->name('crear_requerimiento');
Route::post('requerimientos/crear', 'RequerimientosController@guardar')->name('guardar_requerimiento');
Route::get('requerimientos/{idP}-{idR}/editar', 'RequerimientosController@editar')->name('editar_requerimiento');
Route::put('requerimientos/{idR}/editar', 'RequerimientosController@actualizar')->name('actualizar_requerimiento');
Route::delete('requerimientos/{idP}-{idR}', 'RequerimientosController@eliminar')->name('eliminar_requerimiento');
Route::get('prequerimientos/{id}', 'RequerimientosController@obtenerPorcentaje')->name('obtener_porcentaje_requerimiento');

//Rutas CRUD Actividades
Route::get('actividades/{idP}', 'ActividadesController@index')->name('actividades');
Route::get('actividades/{idP}/crearT', 'ActividadesController@crearTrabajador')->name('crear_actividad_trabajador');
Route::get('actividades/{idP}/crearC', 'ActividadesController@crearCliente')->name('crear_actividad_cliente');
Route::post('actividades/crear', 'ActividadesController@guardar')->name('guardar_actividad');
Route::get('actividades/{idP}-{idR}/editar', 'ActividadesController@editar')->name('editar_actividad');
Route::put('actividades/{idP}-{idR}', 'ActividadesController@actualizar')->name('actualizar_actividad');
Route::delete('actividades/{idP}-{idR}', 'ActividadesController@eliminar')->name('eliminar_actividad');
Route::get('actividades/{idH}/aprobar', 'ActividadesController@aprobarHoras')->name('aprobar_horas_actividad');
Route::put('actividades/{idH}/aprobar', 'ActividadesController@actualizarHoras')->name('actualizar_horas_actividad');
Route::get('actividades/{idA}/terminar-aprobacion', 'ActividadesController@finalizarAprobacion')->name('finalizar_horas_actividad');

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

//Rutas Metricas
Route::get('eficacia', 'MetricasController@metricaEficaciaGeneral')->name('eficacia_general');
Route::get('eficaciad', 'MetricasController@metricasGenerales')->name('metricas_generales');
Route::get('eficiencia', 'MetricasController@metricaEficienciaGeneral')->name('eficiencia_general');
Route::get('efectividad', 'MetricasController@metricaEfectividadGeneral')->name('efectividad_general');
Route::get('barraseficacia', 'MetricasController@barrasEficaciaPorTrabajador')->name('eficacia_barras_trabajador');
Route::get('barraseficiencia', 'MetricasController@barrasEficienciaPorTrabajador')->name('eficiencia_barras_trabajador');
Route::get('barrasefectividad', 'MetricasController@barrasEfectividadPorTrabajador')->name('efectividad_barras_trabajador');

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
});


