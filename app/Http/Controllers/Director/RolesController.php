<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Roles;
use App\Http\Requests\ValidacionRol;
use Illuminate\Database\QueryException;
use App\Models\Tablas\Usuarios;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::where('RLS_Rol_Id', '=', 6)->orderBy('id')->get();
        return view('director.roles.listar', compact('roles', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('director.roles.crear', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionRol $request)
    {
        $roles = Roles::where('RLS_Nombre_Rol', '=', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))->first();
        if($roles){
            return redirect()->back()->withErrors('Ya se encuentra registrado el rol en el sistema')->withInput();
        }
        Roles::create([
            'RLS_Rol_Id' => 6,
            'RLS_Nombre_Rol' => $request->RLS_Nombre_Rol,
            'RLS_Descripcion_Rol' => $request->RLS_Descripcion_Rol,
            'RLS_Empresa_Id' => $request->id
        ]);
        return redirect()->back()->with('mensaje', 'Rol creado con exito');
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $rol = Roles::findOrFail($id);
        return view('director.roles.editar', compact('rol', 'datos'));
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
        $roles = Roles::where('RLS_Nombre_Rol', '<>', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))->get();
        foreach ($roles as $rol) {
            if($rol->RLS_Nombre_Rol==$request->RLS_Nombre_Rol){
                return redirect()->back()->withErrors('Ya se encuentra registrado el rol en el sistema')->withInput();
            }
        }
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
            return redirect()->back()->with('mensaje', 'El Rol fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->back()->withErrors(['El Rol está siendo usada por otro recurso.']);
        }
    }
}
