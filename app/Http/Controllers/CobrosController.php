<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\FacturasCobro;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Carbon;
use PDF;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HistorialEstados;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Respuesta;

/**
 * Cobros Controller, donde se mostrarán las actividades por cobrar
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class CobrosController extends Controller
{
    /**
     * Muestra el listado de actividades pendientes de cobro
     *
     * @return \Illuminate\View\View Vista del listado de actividades para cobrar
     */
    public function index()
    {
        can('listar-cobros');
        
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
        $cobros = Actividades::obtenerActividadesCobrar();
        $proyectos = FacturasCobro::obtenerProyectosFacturasPendientes();
        
        return view(
            'cobros.listar',
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
     * Agrega las actividades a la factura del cliente
     *
     * @param  $idA  Identificador de la Actividad
     * @param  $idA  Identificador del cliente
     * @return redirect()->back()->with()
     */
    public function agregarFactura($idA, $idC)
    {
        $cliente = Usuarios::findOrFail($idC);
        $rta = Respuesta::obtenerUltimaRespuesta($idA);
        $actividad = Actividades::findOrFail($idA);
        $trabajador = Usuarios::findOrFail($actividad->ACT_Trabajador_Id);
        $horas = HorasActividad::obtenerHorasAprobadasActividad($idA);

        Actividades::actualizarEstadoActividad($idA, 8);
        Respuesta::actualizarEstado($rta->last()->Id_Rta, 8);
        FacturasCobro::crearFactura($idA, $idC);
        Actividades::actualizarCostoEstimado(
            $idA,
            (int)$horas->HorasR,
            $trabajador->USR_Costo_Hora
        );
        HistorialEstados::crearHistorialEstado($idA, 8);
        
        return redirect()
            ->back()
            ->with(
                'mensaje',
                'Actividad agregada a la cuenta de cobro del cliente '.
                    $cliente->USR_Nombres_Usuario.
                    ' '.
                    $cliente->USR_Apellidos_Usuario
            );
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

        $fileName = 'CuentaCobroINK-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }
}