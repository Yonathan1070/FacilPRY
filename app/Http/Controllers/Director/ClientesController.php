<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Http\Requests\ValidacionUsuario;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $clientes = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Roles.RLS_Nombre')
            ->where('TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', '5')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.USR_Apellido', 'ASC')
            ->get();
        return view('director.clientes.listar', compact('clientes', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('director.clientes.crear', compact('datos'));
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
            'USR_Empresa_Id' => $request->id
        ]);
        $cliente = Usuarios::where("USR_Documento","=",$request['USR_Documento'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => 5,
            'USR_RLS_Usuario_Id' => $cliente->id,
            'USR_RLS_Estado' => 1
        ]);
        return redirect()->route('crear_cliente_director')->with('mensaje', 'Cliente agregado con exito');
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
        $cliente = Usuarios::findOrFail($id);
        return view('director.clientes.editar', compact('cliente', 'datos'));
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
        Usuarios::findOrFail($id)->update($request->all());
        return redirect()->route('clientes_director')->with('mensaje', 'Cliente actualizado con exito');
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
            return redirect()->route('clientes_director')->with('mensaje', 'El Cliente fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('clientes_director')->withErrors(['El Cliente está siendo usado por otro recurso.']);
        }
    }
}
