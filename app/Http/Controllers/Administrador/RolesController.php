<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionRol;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
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
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::orderBy('id')->get();
        return view('administrador.roles.listar', compact('roles', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('administrador.roles.crear', compact('datos', 'notificaciones', 'cantidad'));
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
            'RLS_Empresa_Id' => session()->get('Empresa_Id')
        ]);
        return redirect()->back()->with('mensaje', 'Rol creado con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $rol = Roles::findOrFail($id);
        if($rol->RLS_Rol_Id == 0)
            return redirect()->back()->withErrors(['El rol es por defecto del sistema, no es posible modificarlo.']);
        return view('administrador.roles.editar', compact('rol', 'datos', 'notificaciones', 'cantidad'));
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
        return redirect()->route('roles_administrador')->with('mensaje', 'Rol actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        if($request->ajax()){
            $rol = Roles::findOrFail($id);
            if($rol->RLS_Rol_Id == 0){
                return response()->json(['mensaje' => 'rd']);
            }else{
                try{
                    Roles::destroy($id);
                    return response()->json(['mensaje' => 'ok']);
                }catch(QueryException $e){
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }
}
