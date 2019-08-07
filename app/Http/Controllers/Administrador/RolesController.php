<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Roles;
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
        $roles = Roles::orderBy('id')->get();
        return view('administrador.roles.listar', compact('roles', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('administrador.roles.crear', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        Roles::create([
            'RLS_Rol_Id' => 6,
            'RLS_Nombre' => $request->RLS_Nombre,
            'RLS_Descripcion' => $request->RLS_Descripcion
        ]);
        return redirect()->route('crear_rol_administrador')->with('mensaje', 'Rol creado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        if($rol->RLS_Rol_Id == 0)
            return redirect('administrador/roles')->withErrors(['El rol es por defecto del sistema, no es posible modificarlo.']);
        return view('administrador.roles.editar', compact('rol', 'datos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $id)
    {
        Roles::findOrFail($id)->update($request->all());
        return redirect()->route('roles_administrador')->with('mensaje', 'Rol actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        $rol = Roles::findOrFail($id);
        if($rol->RLS_Rol_Id == 0)
            return redirect()->route('roles_administrador')->withErrors(['El rol es por defecto del sistema, no es posible eliminarlo.']);
        try{
            Roles::destroy($id);
            return redirect()->route('roles_administrador')->with('mensaje', 'El Rol fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('roles_administrador')->withErrors(['El Rol está siendo usada por otro recurso.']);
        }
    }
}
