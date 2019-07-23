<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Roles;
use App\Http\Requests\ValidacionRol;
use Illuminate\Database\QueryException;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Roles::where('RLS_Rol_Id', '=', 6)->orderBy('id')->get();
        return view('director.roles.listar', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        return view('director.roles.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionRol $request)
    {
        Roles::create([
            'RLS_Rol_Id' => 6,
            'RLS_Nombre' => $request->RLS_Nombre,
            'RLS_Descripcion' => $request->RLS_Descripcion
        ]);
        return redirect()->route('crear_rol_director')->with('mensaje', 'Rol creado con exito');
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
    public function editar($id)
    {
        $rol = Roles::findOrFail($id);
        return view('director.roles.editar', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionRol $request, $id)
    {
        Roles::findOrFail($id)->update($request->all());
        return redirect()->route('roles_director')->with('mensaje', 'Rol actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        try{
            Roles::destroy($id);
            return redirect()->route('roles_director')->with('mensaje', 'El Rol fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('roles_director')->withErrors(['El Rol está siendo usada por otro recurso.']);
        }
    }
}
