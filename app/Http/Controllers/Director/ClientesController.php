<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Http\Requests\ValidacionUsuario;
use Illuminate\Support\Facades\Mail;

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
        $clientes = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'r.RLS_Nombre_Rol')
            ->where('ur.USR_RLS_Rol_Id', '=', '5')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario', 'ASC')
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
            'USR_Tipo_Documento_Usuario' => $request['USR_Tipo_Documento_Usuario'],
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => $request['USR_Direccion_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'password' => bcrypt($request['USR_Nombre_Usuario']),
            'USR_Supervisor_Id' => session()->get('Usuario_Id'),
            'USR_Empresa_Id' => $request->id
        ]);
        $cliente = Usuarios::where("USR_Documento_Usuario","=",$request['USR_Documento_Usuario'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => 5,
            'USR_RLS_Usuario_Id' => $cliente->id,
            'USR_RLS_Estado' => 1
        ]);
        Mail::send('general.correo.bienvenida', [
            'nombre' => $request['USR_Nombres_Usuario'].' '.$request['USR_Apellidos_Usuario'],
            'username' => $request['USR_Nombre_Usuario']], function($message) use ($request){
            $message->from('yonathancam1997@gmail.com', 'FacilPRY');
            $message->to($request['USR_Correo_Usuario'], 'Bienvenido a FacilPRY, Software de Gestión de Proyectos')
                ->subject('Bienvenido '.$request['USR_Nombres_Usuario']);
        });
        if (Mail::failures()) {
            return redirect()->back()->withErrors('Error al envíar el correo.');
        }
        return redirect()->back()->with('mensaje', 'Cliente agregado con exito');
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
            return redirect()->back()->with('mensaje', 'El Cliente fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->back()->withErrors(['El Cliente está siendo usado por otro recurso.']);
        }
    }
}
