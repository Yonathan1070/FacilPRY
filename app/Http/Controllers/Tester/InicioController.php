<?php

namespace App\Http\Controllers\Tester;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Carbon;
use App\Models\Tablas\Usuarios;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesPendientes = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.Id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Requerimientos as r', 'r.Id', '=', 'a.ACT_Requerimiento_Id')
            ->select('af.id as Id_Act_Fin', 'af.*', 'a.*', 'p.*', 'r.*')
            ->where('a.ACT_Estado_Actividad', '=', 'Finalizado')
            ->where('af.ACT_FIN_Estado', '<>', 'Rechazado')
            ->get();
        return view('tester.inicio', compact('actividadesPendientes', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aprobacionactividad($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesPendientes = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.Id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Requerimientos as re', 're.Id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->select('af.id as Id_Act_Fin', 'a.id as Id_Act', 'af.*', 'a.*', 'p.*', 're.*', 'u.*', 'ro.*')
            ->where('af.Id', '=', $id)
            ->first();
        $perfil = DB::table('TBL_Usuarios as u')
            ->join('TBL_Actividades as a', 'a.ACT_Trabajador_Id', '=', 'u.id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('a.id', '=', $actividadesPendientes->Id_Act)
            ->first();
        return view('tester.aprobacion', compact('actividadesPendientes', 'perfil', 'datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function descargarArchivo($ruta)
    {
        $ruta = public_path().'/documentos_soporte/'.$ruta;
        return response()->download($ruta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaRechazado(Request $request)
    {
        ActividadesFinalizadas::findOrFail($request->id)->update([
            'ACT_FIN_Estado'=>'Rechazado',
            'ACT_FIN_Respuesta' => $request->ACT_FIN_Respuesta,
            'ACT_FIN_Fecha_Respuesta' => Carbon::now()
        ]);

        $actividad = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->select('a.id')
            ->where('af.id', '=', $request->id)
            ->first();
        Actividades::findOrFail($actividad->id)->update(['ACT_Estado_Actividad'=>'En Proceso']);
        return redirect()->route('inicio_tester')->with('mensaje', 'Respuesta envíada');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaAprobado($id)
    {
        ActividadesFinalizadas::findOrFail($id)->update(['ACT_FIN_Estado'=>'Aprobado']);
        $actividad = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->select('a.id')
            ->where('af.id', '=', $id)
            ->first();
        Actividades::findOrFail($actividad->id)->update(['ACT_Estado_Actividad'=>'En Cobro']);
        return redirect()->route('inicio_tester')->with('mensaje', 'Respuesta envíada');
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
