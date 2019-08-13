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
use Illuminate\Http\Response;
use App\Models\Tablas\ActividadesFinalizadas;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Empresas;

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

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividadesEstancadas = $this->actividadesEstancadas($hoy);
        $actividadesProceso = $this->actividadesProceso($hoy);
        $actividadesAtrasadas = $this->actividadesAtrasadas($hoy);
        $actividadesFinalizadas = $this->actividadesFinalizadas();
        return view('perfiloperacion.actividades.listar', compact('actividadesEstancadas','actividadesProceso','actividadesAtrasadas', 'actividadesFinalizadas', 'datos'));
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

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = $this->obtenerActividades($id);
        $horasRestantes = $hoy->diffInHours($actividades->ACT_Fecha_Fin_Actividad);

        return view('perfiloperacion/actividades/asignacion', compact('horasRestantes', 'id', 'datos'));
    }

    public function guardarHoras(Request $request)
    {
        if ($request->HRS_ACT_Cantidad_Horas > $request->Horas_Restantes) {
            return redirect()->route('actividades_asignar_horas_perfil_operacion', [$request['HRS_ACT_Actividad_Id']])->withErrors('La cantidad de horas no puede ser superior a las horas que faltan para la entrega de la Actividad');
        }
        HorasActividad::create([
            'HRS_ACT_Actividad_Id' =>$request->Actividad_Id,
            'HRS_ACT_Cantidad_Horas' => $request->HRS_ACT_Cantidad_Horas
        ]);
        Actividades::findOrFail($request->Actividad_Id)->update(['ACT_Estado_Actividad' => 'En Proceso']);
        return redirect()->back()->with('mensaje', 'Horas Asignadas con exito');
    }

    public function generarPdf()
    {
        $hoy = new DateTime();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($datos->USR_Empresa_Id);
        $actividadesEstancadas = $this->actividadesEstancadas($hoy);
        $actividadesProceso = $this->actividadesProceso($hoy);
        $actividadesAtrasadas = $this->actividadesAtrasadas($hoy);
        $actividadesFinalizadas = $this->actividadesFinalizadas();

        $pdf = PDF::loadView('includes.pdf.actividades', compact('actividadesEstancadas','actividadesProceso','actividadesAtrasadas', 'actividadesFinalizadas', 'empresa'));

        $fileName = 'Actividades'.session()->get('Usuario_Nombre');
        return $pdf->download($fileName);
    }

    public function descargarDocumentoSoporte($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
        ->select('a.ACT_Documento_Soporte_Actividad')
        ->where('a.id', '=', $id)
        ->first();
        return response()->download(public_path().'/documentos_soporte/'.$actividad->ACT_Documento_Soporte_Actividad);
    }

    public function finalizar($id)
    {
        $hoy = Carbon::now();
        $hoy->format('Y-m-d H:i:s');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = $this->obtenerActividades($id);

        return view('perfiloperacion.actividades.finalizar', compact('id', 'datos'));
    }

    public function guardarFinalizar(Request $request){
        if ($request->hasFile('ACT_FIN_Documento_Soporte')) {
            if ($request->file('ACT_FIN_Documento_Soporte')->isValid()) {
                $archivo = time().'.'.$request->file('ACT_FIN_Documento_Soporte')->getClientOriginalName();
                $request->ACT_FIN_Documento_Soporte->move(public_path('documentos_soporte'), $archivo);
            }
        }else{
            return redirect()->route('actividades_finalizar_perfil_operacion', [$request['Actividad_Id']])->withErrors('Debe cargar un documento que evidencie la actividad realizada.')->withInput();
        }
        ActividadesFinalizadas::create([
            'ACT_FIN_Descripcion' => $request['ACT_FIN_Descripcion'],
            'ACT_FIN_Documento_Soporte' => $archivo,
            'ACT_FIN_Actividad_Id' => $request['Actividad_Id'],
            'ACT_FIN_Estado' => 'Esperando Aprobación',
            'ACT_FIN_Fecha_Finalizacion' => Carbon::now()
        ]);
        Actividades::findOrFail($request['Actividad_Id'])->update(['ACT_Estado_Actividad' => 'Finalizado']);
        
        return redirect()->route('actividades_perfil_operacion')->with('mensaje', 'Actividad finalizada');
    }

    public function actividadesEstancadas($hoy){
        $actividadesEstancadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->select('a.id AS ID_Actividad','a.*', 'p.*')
            ->where('a.ACT_Estado_Actividad', '=', 'Estancado')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('a.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('a.id', 'ASC')
            ->get();
        return $actividadesEstancadas;
    }

    public function actividadesProceso($hoy){
        $actividadesProceso = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Horas_Actividad as ha', 'ha.HRS_ACT_Actividad_Id', '=', 'a.id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->select('a.id AS ID_Actividad','a.*', 'p.*', 'ha.HRS_ACT_Cantidad_Horas', 'af.*')
            ->where('a.ACT_Estado_Actividad', '=', 'En Proceso')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('a.ACT_Fecha_Fin_Actividad', '>', $hoy)
            ->orderBy('a.id', 'ASC')
            ->groupBy('a.id')
            ->get();

        return $actividadesProceso;
    }

    public function actividadesAtrasadas($hoy){
        $actividadesAtrasadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->select('a.id AS ID_Actividad','a.*', 'af.*', 'p.*', DB::raw('count(af.ACT_FIN_Actividad_Id) as fila'))
            ->where('a.ACT_Estado_Actividad', '<>', 'Finalizado')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('a.ACT_Fecha_Fin_Actividad', '<', $hoy)
            ->orderBy('a.id', 'ASC')
            ->groupBy('af.ACT_FIN_Actividad_Id')
            ->get();

        return $actividadesAtrasadas;
    }

    public function actividadesFinalizadas(){
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.Id')
            ->select('a.id AS ID_Actividad','a.*', 'p.*', 'af.*')
            ->where('a.ACT_Estado_Actividad', '<>', 'En Proceso')
            ->where('a.ACT_Estado_Actividad', '<>', 'Estancado')
            ->where('af.ACT_FIN_Estado', '<>', 'Rechazado')
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->orderBy('a.id', 'ASC')
            ->get();

        return $actividadesFinalizadas;
    }

    public function obtenerActividades($id){
        $actividades = Actividades::select('TBL_Actividades.*')
            ->where('TBL_Actividades.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->where('TBL_Actividades.id', '=', $id)
            ->first();

        return $actividades;
    }
}
