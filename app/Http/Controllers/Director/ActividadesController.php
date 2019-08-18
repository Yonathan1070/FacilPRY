<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionActividad;
use App\Models\Tablas\DocumentosSoporte;
use App\Models\Tablas\HistorialEstados;
use Illuminate\Support\Carbon;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->get();
        if(count($requerimientos)<=0){
            return redirect()->back()->withErrors('No se pueden asignar actividades si no hay requerimientos previamente registrados.');
        }
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.REQ_Proyecto_Id', '=', $idP)
            ->orderBy('a.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($idP);
        return view('director.actividades.listar', compact('actividades', 'proyecto', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($idP)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->orderBy('id', 'ASC')->get();
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*')
            ->where('r.RLS_Rol_Id', '=', '6')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario')
            ->get();
        return view('director.actividades.crear', compact('proyecto', 'perfilesOperacion', 'requerimientos', 'datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionActividad $request)
    {
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()->route('crear_actividad_director', [$request['ACT_Proyecto_Id']])->withErrors('La fecha de inicio no puede ser superior a la fecha de finalización')->withInput();
        }
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->where('REQ_Proyecto_Id', '=', $request->ACT_Proyecto_Id)
            ->get();
        foreach ($actividades as $actividad) {
            if($actividad->ACT_Nombre_Actividad == $request->ACT_Nombre_Actividad){
                return redirect()->route('crear_actividad_director', [$request['ACT_Proyecto_Id']])->withErrors('Ya hay registrada una actividad con el mismo nombre.')->withInput();
            }
        }
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].' 23:59:00',
            'ACT_Costo_Actividad' => 0,
            'ACT_Requerimiento_Id' => $request['ACT_Requerimiento_Id'],
            'ACT_Trabajador_Id' => $request['ACT_Usuario_Id'],
        ]);
        $actividad = Actividades::orderByDesc('created_at')->take(1)->first();
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => 1,
            'HST_EST_Actividad' => $actividad->id
        ]);
        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            foreach ($request->file('ACT_Documento_Soporte_Actividad') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('documentos_soporte'), $archivo);
                    DocumentosSoporte::create([
                        'DOC_Actividad_Id' => $actividad->id,
                        'ACT_Documento_Soporte_Actividad' => $archivo
                    ]);
                }
            }
        }
        return redirect()->route('crear_actividad_director', [$request['ACT_Proyecto_Id']])->with('mensaje', 'Actividad agregada con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($idP, $idR)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP)->first();
        $requerimiento = Requerimientos::findOrFail($idR)->first();
        return view('director.requerimientos.editar', compact('proyecto', 'requerimiento', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionActividad $request, $idR)
    {
        Requerimientos::findOrFail($idR)->update($request->all());
        return redirect()->route('requerimientos_director', [$request['REQ_Proyecto_Id']])->with('mensaje', 'Requerimiento actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($idP, $idR)
    {
        try{
            Requerimientos::destroy($idR);
            return redirect()->route('requerimientos_director', [$idP])->with('mensaje', 'El Requerimiento fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('requerimientos_director', [$idP])->withErrors(['El Requerimiento está siendo usada por otro recurso.']);
        }
    }
}
