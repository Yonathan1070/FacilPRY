<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use Illuminate\Http\Request;
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
        can('listar-clientes');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $clientes = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'r.RLS_Nombre_Rol')
            ->where('ur.USR_RLS_Rol_Id', '=', '5')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->orderBy('u.USR_Apellidos_Usuario', 'ASC')
            ->get();
        return view('clientes.listar', compact('clientes', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        can('crear-clientes');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('clientes.crear', compact('datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionUsuario $request)
    {
        Usuarios::crearUsuario($request);
        $cliente = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
        UsuariosRoles::asignarRol(5, $cliente->id);
        MenuUsuario::asignarMenuCliente($cliente->id);

        Mail::send('general.correo.bienvenida', [
            'nombre' => $request['USR_Nombres_Usuario'] . ' ' . $request['USR_Apellidos_Usuario'],
            'username' => $request['USR_Nombre_Usuario']
        ], function ($message) use ($request) {
            $message->from('yonathancam1997@gmail.com', 'FacilPRY');
            $message->to($request['USR_Correo_Usuario'], 'Bienvenido a FacilPRY, Software de Gestión de Proyectos')
                ->subject('Bienvenido ' . $request['USR_Nombres_Usuario']);
        });

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' ha creado el usuario ' . $request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'person_add'
        );
        Notificaciones::crearNotificacion(
            'Hola! ' . $request->USR_Nombres_Usuario . ' ' . $request->USR_Apellidos_Usuario . ', Bienvenido a FacilPRY, revise sus datos.',
            session()->get('Usuario_Id'),
            $cliente->id,
            'perfil',
            null,
            null,
            'account_circle'
        );
        if (Mail::failures()) {
            return redirect()->back()->withErrors('Cliente agregado con exito, Error al Envíar Correo, por favor verificar que esté correcto');
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
        can('editar-clientes');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Usuarios::findOrFail($id);
        return view('clientes.editar', compact('cliente', 'datos', 'notificaciones', 'cantidad'));
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Usuarios::editarUsuario($request, $id);
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' ha actualizado los datos de ' . $request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'update'
        );
        Notificaciones::crearNotificacion(
            $request->USR_Nombres_Usuario . ' ' . $request->USR_Apellidos_Usuario . ', susdatos fueron actualizados',
            session()->get('Usuario_Id'),
            $id,
            'perfil',
            null,
            null,
            'update'
        );
        return redirect()->route('clientes')->with('mensaje', 'Cliente actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        if (!can('eliminar-clientes')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
                    $datosU = Usuarios::findOrFail($id);
                    Usuarios::destroy($id);
                    Notificaciones::crearNotificacion(
                        $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' ha eliminado al usuario ' . $datosU->USR_Nombres_Usuario,
                        session()->get('Usuario_Id'),
                        $datos->USR_Supervisor_Id,
                        null,
                        null,
                        null,
                        'delete_forever'
                    );
                    return response()->json(['mensaje' => 'ok']);
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }
}
