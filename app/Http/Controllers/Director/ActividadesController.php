<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Actividades;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        
        $actividades = DB::table('TBL_Actividades')
            ->join('TBL_Proyectos', 'TBL_Proyectos.Id', '=', 'TBL_Actividades.ACT_Proyecto_Id')
            ->join('TBL_Usuarios', 'TBL_Usuarios.Id', 'TBL_Actividades.ACT_Usuario_Id')
            ->where('TBL_Actividades.ACT_Proyecto_Id', '=', $idP)
            ->orderBy('TBL_Actividades.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($idP)->first();
        return view('director.actividades.listar', compact('actividades', 'proyecto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($idP)
    {
        $proyecto = Proyectos::findOrFail($idP)->first();
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $idP)->orderBy('id', 'ASC')->get();
        $perfilesOperacion = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*')
            ->where('TBL_Roles.RLS_Rol_Id', '=', '6')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.USR_Apellido', 'ASC')
            ->get();
        return view('director.actividades.crear', compact('proyecto', 'perfilesOperacion', 'requerimientos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        if ($request['ACT_Fecha_Inicio_Actividad'] > $request['ACT_Fecha_Fin_Actividad']) {
            return redirect()->route('crear_actividad_director', [$request['ACT_Proyecto_Id']])->withErrors('La fecha de inicio no puede ser superior a la fecha de finalización')->withInput();
        }
        $ruta = null;
        if ($request->hasFile('ACT_Documento_Soporte_Actividad')) {
            if ($request->file('ACT_Documento_Soporte_Actividad')->isValid()) {
                $archivo = time().'.'.$request->file('ACT_Documento_Soporte_Actividad')->getClientOriginalName();
                $mover = $request->ACT_Documento_Soporte_Actividad->move(public_path('documentos_soporte'), $archivo);
                $ruta = $mover->getRealPath();
            }
        }
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Documento_Soporte_Actividad' => $ruta,
            'ACT_Estado_Actividad' => 'Estancado',
            'ACT_Proyecto_Id' => $request['ACT_Proyecto_Id'],
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].' 23:59:00',
            'ACT_Costo_Actividad' => $request['ACT_Costo_Actividad'],
            'ACT_Usuario_Id' => $request['ACT_Usuario_Id'],
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
        $proyecto = Proyectos::findOrFail($idP)->first();
        $requerimiento = Requerimientos::findOrFail($idR)->first();
        return view('director.requerimientos.editar', compact('proyecto', 'requerimiento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $idR)
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
