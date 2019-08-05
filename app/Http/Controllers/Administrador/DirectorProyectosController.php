<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DirectorProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'))->first();
        $directores = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Usuarios_Roles.*', 'TBL_Roles.*')
            ->where('TBL_Roles.Id', '=', '2')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.id', 'ASC')
            ->get();
        //dd($directores);
        return view('administrador.director.listar', compact('directores', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        return view('administrador.director.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionUsuario $request)
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
        $director = Usuarios::where("USR_Documento","=",$request['USR_Documento'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => 2,
            'USR_RLS_Usuario_Id' => $director->id,
            'USR_RLS_Estado' => 1
        ]);
        return redirect()->route('crear_director_administrador')->with('mensaje', 'Director de Proyectos agregado con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $director = Usuarios::findOrFail($id);
        return view('administrador.director.editar', compact('director'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionUsuario $request, $id)
    {
        $director = Usuarios::findOrFail($id)->update([
            'USR_Documento' => $request['USR_Documento'],
            'USR_Nombre' => $request['USR_Nombre'],
            'USR_Apellido' => $request['USR_Apellido'],
            'USR_Fecha_Nacimiento' => $request['USR_Fecha_Nacimiento'],
            'USR_Direccion_Residencia' => $request['USR_Direccion_Residencia'],
            'USR_Telefono' => $request['USR_Telefono'],
            'USR_Correo' => $request['USR_Correo'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
        ]);
        return redirect()->route('directores_administrador')->with('mensaje', 'Director de Proyectos actualizado con exito');
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
            Usuarios::destroy($id);
            return redirect()->route('directores_administrador')->with('mensaje', 'Director de proyectos eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('directores_administrador')->withErrors(['Director de Proyectos está siendo usado por otro recurso.']);
        }
    }
}
