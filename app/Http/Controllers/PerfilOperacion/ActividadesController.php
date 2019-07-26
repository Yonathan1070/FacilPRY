<?php

namespace App\Http\Controllers\PerfilOperacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\Tablas\Actividades;
use Illuminate\Support\Carbon;
use App\Models\Tablas\HorasActividad;
use PDF;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        $actividadesEstancadas = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '=', 'Estancado')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();
        $actividadesProceso = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->join('TBL_Horas_Actividad', 'TBL_Horas_Actividad.HRS_ACT_Actividad_Id', '=', 'TBL_Actividades.id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*', 'TBL_Horas_Actividad.HRS_ACT_Cantidad_Horas')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '=', 'En Proceso')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();
        $actividadesAtrasadas = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '<>', 'Finalizado')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '<', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();
        return view('perfiloperacion.actividades.listar', compact('actividadesEstancadas','actividadesProceso','actividadesAtrasadas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function asignarHoras($id)
    {
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $actividades = Actividades::select('TBL_Actividades.*')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.id', '=', $id)
            ->first();
        $horasRestantes = $hoy->diffInHours($actividades->ACT_Fecha_Fin_Actividad);

        return view('perfiloperacion/actividades/asignacion', compact('horasRestantes', 'id'));
    }

    public function guardarHoras(Request $request)
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        if ($request->HRS_ACT_Horas > $request->Horas_Restantes) {
            return redirect()->route('actividades_asignar_horas_perfil_operacion', [$request['Id_Actividad']])->withErrors('La cantidad de horas no puede ser superior a las horas que faltan para la entrega de la Actividad');
        }
        HorasActividad::create($request->all());
        Actividades::findOrFail($request->HRS_ACT_Actividad_Id)->update(['ACT_Estado_Actividad' => 'En Proceso']);
        return redirect()->route('actividades_perfil_operacion')->with('mensaje', 'Horas Asignadas con exito');
    }

    public function generarPdf()
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        $actividadesEstancadas = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '=', 'Estancado')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();
        $actividadesProceso = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->join('TBL_Horas_Actividad', 'TBL_Horas_Actividad.HRS_ACT_Actividad_Id', '=', 'TBL_Actividades.id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*', 'TBL_Horas_Actividad.HRS_ACT_Cantidad_Horas')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '=', 'En Proceso')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();
        $actividadesAtrasadas = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->select('TBL_Actividades.id AS ID_Actividad','TBL_Actividades.*', 'TBL_Proyectos.*')
            ->where('TBL_Actividades.ACT_Estado_Actividad', '<>', 'Finalizado')
            ->where('TBL_Actividades.ACT_Usuario_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.ACT_Fecha_Fin_Actividad', '<', $hoy)
            ->orderBy('TBL_Actividades.id', 'ASC')
            ->get();

        $pdf = PDF::loadView('includes.pdf.actividades', compact('actividadesEstancadas','actividadesProceso','actividadesAtrasadas'));

        $fileName = 'Actividades'.session()->get('Usuario_Nombre');
        return $pdf->download($fileName);
    }
}
