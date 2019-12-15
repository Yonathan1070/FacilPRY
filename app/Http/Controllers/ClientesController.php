<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        can('listar-clientes');
        $permisos = ['crear'=> can2('crear-clientes'), 'editar'=>can2('editar-clientes'), 'eliminar'=>can2('eliminar-clientes')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $clientes = DB::table('TBL_Usuarios as uu')
            ->join('TBL_Usuarios as ud', 'ud.id', '=', 'uu.USR_Supervisor_Id')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'uu.USR_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'ud.USR_Empresa_Id')
            ->join('TBL_Usuarios_Roles as ur', 'uu.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('uu.*', 'r.RLS_Nombre_Rol')
            ->where('ur.USR_RLS_Rol_Id', '=', '5')
            ->where('ur.USR_RLS_Estado', '=', '1')
            ->where('eu.id', '=', $id)->get();
        $empresa = Empresas::findOrFail($id);
        return view('clientes.listar', compact('clientes', 'empresa', 'datos', 'notificaciones', 'cantidad', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($id)
    {
        can('crear-clientes');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        return view('clientes.crear', compact('datos', 'notificaciones', 'cantidad', 'empresa'));
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
        PermisoUsuario::asignarPermisoPerfil($cliente->id);
        Usuarios::enviarcorreo($request, 'Bienvenido a InkBrutalPRY, Software de Gestión de Proyectos', 'Bienvenido ' . $request['USR_Nombres_Usuario'], 'general.correo.bienvenida');

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
            'Hola! ' . $request->USR_Nombres_Usuario . ' ' . $request->USR_Apellidos_Usuario . ', Bienvenido a InkBrutalPRY, revise sus datos.',
            session()->get('Usuario_Id'),
            $cliente->id,
            'perfil',
            null,
            null,
            'account_circle'
        );
        
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
    public function editar($idC, $idE)
    {
        can('editar-clientes');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $cliente = Usuarios::findOrFail($idC);
        $empresa = Empresas::findOrFail($idE);
        return view('clientes.editar', compact('cliente', 'empresa', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $idC, $idE)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Usuarios::editarUsuario($request, $idC);
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
            $idC,
            'perfil',
            null,
            null,
            'update'
        );
        return redirect()->route('clientes', ['id'=>$idE])->with('mensaje', 'Cliente actualizado con exito');
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
