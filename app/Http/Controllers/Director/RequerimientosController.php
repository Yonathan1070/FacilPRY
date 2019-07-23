<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;

class RequerimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        $requerimientos = DB::table('TBL_Requerimientos')
            ->join('TBL_Proyectos', 'TBL_Proyectos.Id', '=', 'TBL_Requerimientos.REQ_Proyecto_Id')
            ->where('TBL_Requerimientos.REQ_Proyecto_Id', '=', $idP)
            ->orderBy('TBL_Requerimientos.Id', 'ASC')
            ->get();
        $proyecto = Proyectos::findOrFail($idP)->first();
        return view('director.requerimientos.listar', compact('requerimientos', 'proyecto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($idP)
    {
        $proyecto = Proyectos::findOrFail($idP)->first();
        return view('director.requerimientos.crear', compact('proyecto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
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
