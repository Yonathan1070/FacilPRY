<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Models\Tablas\Roles;

class PerfilOperacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perfilesOperacion = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Roles.RLS_Nombre')
            ->where('TBL_Roles.RLS_Rol_Id', '=', '6')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.USR_Apellido', 'ASC')
            ->get();
        return view('director.perfiloperacion.listar', compact('perfilesOperacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $roles = Roles::where('RLS_Rol_Id', '=', 6)->orderBy('id')->get();
        return view('director.perfiloperacion.crear', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        Usuarios::create([
            'USR_Tipo_Documento' => $request['USR_Tipo_Documento'],
            'USR_Documento' => $request['USR_Documento'],
            'USR_Nombre' => $request['USR_Nombre'],
            'USR_Apellido' => $request['USR_Apellido'],
            'USR_Fecha_Nacimiento' => $request['USR_Fecha_Nacimiento'],
            'USR_Direccion_Residencia' => $request['USR_Direccion_Residencia'],
            'USR_Telefono' => $request['USR_Telefono'],
            'USR_Correo' => $request['USR_Correo'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'password' => bcrypt($request['password']),
        ]);
        $perfil = Usuarios::where("USR_Documento","=",$request['USR_Documento'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => $request['USR_RLS_Rol_Id'],
            'USR_RLS_Usuario_Id' => $perfil->id,
            'USR_RLS_Estado' => 1
        ]);
        return redirect()->route('crear_perfil_director')->with('mensaje', 'Perfil de Operación agregado con exito');
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
        $perfil = Usuarios::findOrFail($id);
        return view('director.perfiloperacion.editar', compact('perfil'));
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
        Usuarios::findOrFail($id)->update($request->all());
        return redirect()->route('perfil_director')->with('mensaje', '¨Perfi de operación  actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        try{
            Usuarios::destroy($id);
            return redirect()->route('perfil_director')->with('mensaje', 'El Perfil de operación fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('perfil_director')->withErrors(['El Perfil de operación está siendo usada por otro recurso.']);
        }
    }
}
