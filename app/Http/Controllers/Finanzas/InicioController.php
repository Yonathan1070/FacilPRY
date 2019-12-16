<?php

namespace App\Http\Controllers\Finanzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Carbon;
use PDF;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cobros = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id as Id_Actividad', 'u.id as Id_Cliente', 'a.*', 'u.*', 'p.*')
            ->where('e.id', '=', 8)
            ->where('a.ACT_Costo_Estimado_Actividad', '<>', 0)
            ->orderBy('p.id')
            ->get();
        $proyectos = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->select('p.id as Id_Proyecto', 'a.*', 'p.*', 'u.*', DB::raw('COUNT(a.id) as No_Actividades'))
            ->where('a.ACT_Costo_Real_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->groupBy('fc.FACT_Cliente_Id')
            ->get();

            return view('finanzas.inicio', compact('cobros', 'proyectos', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function agregarCosto($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Usuarios as us', 'us.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as urs', 'urs.USR_RLS_Usuario_Id', '=', 'us.id')
            ->join('TBL_Roles as ros', 'ros.id', '=', 'urs.USR_RLS_Rol_Id')
            ->leftJoin('TBL_Documentos_Soporte as ds', 'ds.DOC_Actividad_Id', '=', 'a.id')
            ->leftjoin('TBL_Documentos_Evidencias as de', 'de.DOC_Actividad_Finalizada_Id', '=', 'af.id')
            ->select('af.*', 'a.*', 'p.*', 'u.*', 'ro.*', 'ds.*', 'de.*', 'us.USR_Nombres_Usuario as NombreT', 'us.USR_Apellidos_Usuario as ApellidoT', 'ros.RLS_Nombre_Rol as RolT')
            ->where('a.id', '=', $id)
            ->first();
        return view('finanzas.cobro', compact('datos', 'actividades', 'perfil', 'id', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizarCosto(Request $request)
    {
        Actividades::findOrFail($request->id)->update([
            'ACT_Estado_Id' => 9,
            'ACT_Costo_Real_Actividad' => $request->ACT_Costo_Actividad
        ]);
        return redirect()->route('inicio_finanzas')->with('mensaje', 'Costo agregado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
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

        //return view('includes.pdf.factura.factura', compact('datos'));
        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));

        $fileName = 'FacturaINK-'.$factura;
        return $pdf->download($fileName);
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
