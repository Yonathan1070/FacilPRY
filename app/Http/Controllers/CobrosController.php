<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Facades\DB;
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cobros = Actividades::obtenerActividadesCobrar();
        $proyectos = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->select('p.id as Id_Proyecto', 'a.*', 'p.*', 'u.*', DB::raw('COUNT(a.id) as No_Actividades'))
            ->where('a.ACT_Costo_Estimado_Actividad', '<>', 0)
            ->where('e.id', '=', 8)
            ->orWhere('e.id', '=', 9)
            ->groupBy('fc.FACT_Cliente_Id')
            ->get();
        return view('cobros.listar', compact('cobros', 'proyectos', 'datos', 'notificaciones', 'cantidad'));
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
        Actividades::actualizarEstadoActividad($idA, 8);
        $rta = Respuesta::obtenerUltimaRespuesta($idA);
        Respuesta::actualizarEstado($rta->last()->Id_Rta, 8);
        FacturasCobro::crearFactura($idA, $idC);
        $actividad = Actividades::findOrFail($idA);
        $trabajador = Usuarios::findOrFail($actividad->ACT_Trabajador_Id);
        $horas = HorasActividad::obtenerHorasAprobadasActividad($idA);
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
                'Actividad agregada a la factura del cliente '.
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
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'eu.EMP_Empresa_Id')
            ->select('ed.id')
            ->first()->id;
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
            'empresa'=>$empresa
        ];

        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }
}
