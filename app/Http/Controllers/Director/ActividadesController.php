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

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->where('a.ACT_Proyecto_Id', '=', $idP)
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
            ->orderBy('u.USR_Apellido', 'ASC')
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
        $archivo = null;
        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            if ($request->file('ACT_Documento_Soporte_Actividad')->isValid()) {
                $archivo = time().'.'.$request->file('ACT_Documento_Soporte_Actividad')->getClientOriginalName();
                $request->ACT_Documento_Soporte_Actividad->move(public_path('documentos_soporte'), $archivo);
            }
        }
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Documento_Soporte_Actividad' => $archivo,
            'ACT_Estado_Actividad' => 'Estancado',
            'ACT_Proyecto_Id' => $request['ACT_Proyecto_Id'],
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].' 23:59:00',
            'ACT_Costo_Actividad' => 0,
            'ACT_Trabajador_Id' => $request['ACT_Usuario_Id'],
            'ACT_Requerimiento_Id' => $request['ACT_Requerimiento_Id'],
        ]);
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
