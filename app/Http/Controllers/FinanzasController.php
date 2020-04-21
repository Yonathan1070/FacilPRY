<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\DocumentosEvidencias;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\FacturaAdicional;
use App\Models\Tablas\FacturasCobro;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use PDF;

/**
 * Finanzas Controller, donde se mostrarán las
 * facturas y las actividades para asignar precio
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class FinanzasController extends Controller
{
    /**
     * Muestra las actividades pendientes para asignar precios
     *
     * @return \Illuminate\View\View Vista del listado de actividades
     */
    public function index()
    {
        can('finanzas');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $cobros = Actividades::obtenerActividadesAsignarCosto();
        $proyectos = Proyectos::obtenerProyectosConFacturas();
        $factAdicional = Proyectos::obtenerProyectosConFacturasAdicionales();

        return view(
            'finanzas.inicio',
            compact(
                'cobros',
                'proyectos',
                'factAdicional',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Muestra la vista detallada para asignar el costo de la actividad
     *
     * @param  $id  Identificador de la actividad
     * @return \Illuminate\View\View Vista detallada de la actividad a cobrar
     */
    public function agregarCosto($id)
    {
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $actividades = ActividadesFinalizadas::obtenerActividadFinalizadaDetalle($id);
        $documentosEvidencias = DocumentosEvidencias::obtenerDocumentosActividadCobrar($id);
        $documentosSoporte = DocumentosSoporte::obtenerDocumentosActividadCobrar($id);
        
        return view(
            'finanzas.cobro',
            compact(
                'datos',
                'actividades',
                'documentosEvidencias',
                'documentosSoporte',
                'id',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Actualiza el costo real de la actividad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->route()
     */
    public function actualizarCosto(Request $request)
    {
        Actividades::actualizarCostoReal(
            $request->id,
            9,
            $request->ACT_Costo_Actividad
        );
        
        return redirect()
            ->route('inicio_finanzas')
            ->with('mensaje', 'Costo agregado.');
    }

    /**
     * Generar PDF de la factura 
     *
     * @param  $id  Identificador del proyecto
     * @return \Illuminate\Http\Response
     */
    public function generarFactura($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturasCobro::obtenerTotalFactura($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
        ];

        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Generar PDF de la factura 
     *
     * @param  $id  Identificador del proyecto
     * @return \Illuminate\Http\Response
     */
    public function generarFacturaAdicional($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturaAdicional::obtenerDetalleFacturaAdicional($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturaAdicional::obtenerTotalFacturaAdicional($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
        ];

        $pdf = PDF::loadView('includes.pdf.factura.facturaadicional', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Muestra formulario para agregar un costo adicional
     *
     * @return \Illuminate\View\View Vista del formulario para agregar un costo
     */
    public function agregarCostosFactura()
    {
        can('finanzas');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);

        $clientes = Usuarios::obtenerTodosClientes();

        return view(
            'finanzas.agregarcobro',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas',
                'clientes'
            )
        );
    }

    /**
     * Obtiene los proyectos del cliente seleccionado
     *
     * @param  $id  Identificador del cliente
     * @return response()->json()
     */
    public function obtenerProyectos($id)
    {
        $proyectos = Proyectos::obtenerProyectosCliente($id);

        return json_encode($proyectos);
    }

    /**
     * Guarda el cobro adicional en la Base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idR  Identificador del requerimiento
     * @return redirect()->route()
     */
    public function guardarCostosFactura(Request $request)
    {
        FacturaAdicional::crearFactura($request);

        Notificaciones::crearNotificacion(
            'Se ha creado una factura adicional',
            session()->get('Usuario_Id'),
            $request->ClienteSelect,
            'inicio_cliente',
            null,
            null,
            'attach_money'
        );

        $para = Usuarios::findOrFail($request->ClienteSelect);
        $de = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        Mail::send('general.correo.informacion', [
            'titulo' => 'Costo adicional agregado',
            'nombre' => $para['USR_Nombres_Usuario'].' '.$para['USR_Apellidos_Usuario'],
            'contenido' => $para['USR_Nombres_Usuario'].
                ', revisa la plataforma InkBrutalPry, '.
                $de['USR_Nombres_Usuario'].
                ' '.
                $de['USR_Apellidos_Usuario'].
                ' le ha agregado un costo adicional a tu proyecto por concepto de '.
                $request['FACT_AD_Descripcion']
        ], function($message) use ($para){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to(
                $para['USR_Correo_Usuario'], 'InkBrutalPRY, Software de Gestión de Proyectos'
            )
                ->subject('Factura Adicional');
        });

        return redirect()
            ->route('agregar_cobro_finanzas')
            ->with('mensaje', 'Factura agregada con exito');
    }

    /**
     * Muestra formulario para editar los costos adicionales
     *
     * @return \Illuminate\View\View Vista del formulario para editar los costos
     */
    public function editarCostosFactura($id)
    {
        can('finanzas');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $proyecto = Proyectos::findOrFail($id);
        $cobros = FacturaAdicional::where('FACT_AD_Proyecto_Id', '=', $id)
            ->where('FACT_AD_Estado_Id', '=', 9)
            ->get();

        return view(
            'finanzas.editarcobro',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas',
                'proyecto',
                'cobros'
            )
        );
    }

    /**
     * Actualiza los costos adicionales
     *
     * @param  $id  Identificador del costo adicional
     * @return response()->json()
     */
    public function actualizarCostosFactura(Request $request, $id)
    {
        if($request->FACT_AD_Descripcion != null) {
            try {
                FacturaAdicional::findOrFail($id)
                    ->update([
                        'FACT_AD_Descripcion'=>$request->FACT_AD_Descripcion
                    ]);
                
                return response()->json(['msg' => 'successDescripcion']);
            } catch(QueryException $ex) {
                return response()->json(['msg' => 'errorDescripcion']);
            }
        } else if($request->FACT_AD_Precio_Factura != null) {
            try {
                FacturaAdicional::findOrFail($id)
                    ->update([
                        'FACT_AD_Precio_Factura'=>$request->FACT_AD_Precio_Factura
                    ]);
                
                return response()->json(['msg' => 'successPrecio']);
            } catch(QueryException $ex) {
                return response()->json(['msg' => 'errorPrecio']);
            }
        }
    }
}