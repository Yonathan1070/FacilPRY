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
use App\Models\Tablas\FacturasCobro;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use Illuminate\Support\Carbon;
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cobros = Actividades::obtenerActividadesAsignarCosto();
        $proyectos = Proyectos::obtenerProyectosConFacturas();

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();

        return view(
            'finanzas.inicio',
            compact(
                'cobros',
                'proyectos',
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = ActividadesFinalizadas::obtenerActividadFinalizadaDetalle($id);
        $documentosEvidencias = DocumentosEvidencias::obtenerDocumentosActividadCobrar($id);
        $documentosSoporte = DocumentosSoporte::obtenerDocumentosActividadCobrar($id);

        $asignadas = Actividades::obtenerActividadesProcesoPerfil();
        
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
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
        $total = FacturasCobro::obtenerTotalFactura($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];

        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }
}
