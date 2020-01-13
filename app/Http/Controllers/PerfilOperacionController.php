<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\UsuariosRoles;
use App\Models\Tablas\Roles;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;

class PerfilOperacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-perfil-operacion');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'ur.*', 'r.RLS_Nombre_Rol')
            ->where('r.RLS_Rol_Id', '=', '4')
            ->orderBy('u.USR_Apellidos_Usuario', 'ASC')
            ->get();
        return view('director.perfiloperacion.listar', compact('perfilesOperacion', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        can('crear-perfil-operacion');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::where('id', '<>', '4')->where('RLS_Rol_Id', '=', 4)->orderBy('id')->get();
        return view('director.perfiloperacion.crear', compact('roles', 'datos', 'notificaciones', 'cantidad'));
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
        $perfil = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
        UsuariosRoles::asignarRol($request['USR_RLS_Rol_Id'], $perfil->id);
        MenuUsuario::asignarMenuPerfilOperacion($perfil->id);
        PermisoUsuario::asignarPermisoPerfil($perfil->id);
        PermisoUsuario::asignarPermisosPerfilOperacion($perfil->id);
        Usuarios::enviarcorreo($request, 'Bienvenido(a) a InkBrutalPRY, Software de Gestión de Proyectos', 'Bienvenido(a) '.$request['USR_Nombres_Usuario'], 'general.correo.bienvenida');

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha creado el usuario '.$request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'perfil_operacion', null, null,
            'person_add'
        );
        Notificaciones::crearNotificacion(
            'Hola! '.$request->USR_Nombres_Usuario.' '.$request->USR_Apellidos_Usuario.', Bienvenido a InkBrutalPRY, verifique sus datos.',
            session()->get('Usuario_Id'),
            $perfil->id,
            'perfil',
            null, null,
            'account_circle'
        );

        return redirect()->back()->with('mensaje', 'Perfil de Operación agregado con exito');
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
        can('editar-perfil-operacion');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $perfil = Usuarios::findOrFail($id);
        return view('director.perfiloperacion.editar', compact('perfil', 'datos', 'notificaciones', 'cantidad'));
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
        Usuarios::editarUsuario($request, $id);
        Notificaciones::crearNotificacion(
            $request->USR_Nombres_Usuario.' '.$request->USR_Apellidos_Usuario.', sus datos fueron actualizados',
            session()->get('Usuario_Id'),
            $id,
            'perfil',
            null,
            null,
            'update'
        );
        return redirect()->route('perfil_operacion')->with('mensaje', 'Perfi de operación  actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        if ($request->ajax()) {
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $datosU = Usuarios::findOrFail($id);
            $usuario = Usuarios::findOrFail($id);
            if($usuario != null){
                if($datos->USR_Supervisor_Id == 0)
                    $datos->USR_Supervisor_Id = 1;
                
                UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)->update(['USR_RLS_Estado' => 0]);
                Notificaciones::crearNotificacion(
                    $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha dejado inactivo al usuario '.$datosU->USR_Nombres_Usuario,
                    session()->get('Usuario_Id'),
                    $datos->USR_Supervisor_Id,
                    'perfil_operacion',
                    null,
                    null,
                    'arrow_downward'
                );
                return response()->json(['mensaje' => 'ok']);
            }else{
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }

    public function agregar($id)
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::findOrFail($id);
        $usuario = Usuarios::findOrFail($id);
        if($usuario != null){
            if($datos->USR_Supervisor_Id == 0)
                $datos->USR_Supervisor_Id = 1;
            
            UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)->update(['USR_RLS_Estado' => 1]);
            Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha dejado activo al usuario '.$datosU->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'perfil_operacion',
                null,
                null,
                'arrow_upward'
            );
        }
        return redirect()->route('perfil_operacion')->with('mensaje', 'Perfil de operación reingresado con exito');
    }
}
