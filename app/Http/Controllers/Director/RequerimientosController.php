<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionRequerimiento;

class RequerimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimientos = DB::table('TBL_Proyectos')
            ->join('TBL_Requerimientos', 'TBL_Proyectos.Id', '=', 'TBL_Requerimientos.REQ_Proyecto_Id')
            ->where('TBL_Requerimientos.REQ_Proyecto_Id', '=', $idP)
            ->orderBy('TBL_Requerimientos.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($idP);
        return view('director.requerimientos.listar', compact('requerimientos', 'proyecto', 'datos'));
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
        return view('director.requerimientos.crear', compact('proyecto', 'datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionRequerimiento $request)
    {
        Requerimientos::create($request->all());
        return redirect()->route('crear_requerimiento_director', [$request['REQ_Proyecto_Id']])->with('mensaje', 'Requerimiento agregado con exito');
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
        $proyecto = Proyectos::findOrFail($idP);
        $requerimiento = Requerimientos::findOrFail($idR);
        return view('director.requerimientos.editar', compact('proyecto', 'requerimiento', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionRequerimiento $request, $idR)
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
