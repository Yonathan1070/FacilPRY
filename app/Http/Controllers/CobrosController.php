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
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Respuesta;

class CobrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-cobros');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cobros = DB::table('TBL_Actividades as a')
            ->join('TBL_Actividades_Finalizadas as af', 'af.ACT_Fin_Actividad_Id', '=', 'a.id')
            ->join('TBL_Respuesta as re', 're.RTA_Actividad_Finalizada_Id', '=', 'af.id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 're.RTA_Estado_Id')
            ->select('a.id as Id_Actividad', 'u.id as Id_Cliente', 'a.*', 'u.*', 'p.*')
            ->where('e.id', '=', 7)
            ->orderBy('p.id')
            ->get();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function agregarFactura($idA, $idC)
    {

        $cliente = Usuarios::findOrFail($idC);
        Actividades::findOrFail($idA)->update(['ACT_Estado_Id' => 8]);
        $rta = DB::table('TBL_Respuesta as re')
            ->join('TBL_Actividades_Finalizadas as af', 'af.id', '=', 'RTA_Actividad_Finalizada_Id')
            ->where('af.ACT_FIN_Actividad_Id', '=', $idA)
            ->select('re.id as Id_Rta')->get();
        Respuesta::findOrFail($rta->last()->Id_Rta)->update(['RTA_Estado_Id' => 8]);
        FacturasCobro::create([
            'FACT_Actividad_Id' => $idA,
            'FACT_Cliente_Id' => $idC,
            'FACT_Fecha_Cobro' => Carbon::now()
        ]);
        $actividad = Actividades::findOrFail($idA);
        $trabajador = Usuarios::findOrFail($actividad->ACT_Trabajador_Id);
        $horas = DB::table('TBL_Horas_Actividad as ha')
            ->select(DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Reales) as HorasR'))
            ->where('ha.HRS_ACT_Actividad_Id', '=', $idA)
            ->first();
        Actividades::findOrFail($idA)->update(['ACT_Costo_Estimado_Actividad' => ((int)$horas->HorasR * $trabajador->USR_Costo_Hora)]);
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 8,
            'HST_EST_Actividad' => $idA
        ]);
        return redirect()->back()->with('mensaje', 'Actividad agregada a la factura del cliente '.$cliente->USR_Nombres_Usuario.' '.$cliente->USR_Apellidos_Usuario);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generarFactura($id)
    {
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'r.*', 'fc.*')
            ->where('a.ACT_Costo_Real_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->where('p.id', '=', $id)
            ->get();
        $idEmpresa = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'eu.EMP_Empresa_Id')
            ->select('ed.id')
            ->first()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Real_Actividad) as Costo'))
            ->groupBy('r.REQ_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->first();
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
        return $pdf->download($fileName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
