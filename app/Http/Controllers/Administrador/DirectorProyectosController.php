<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\MenuUsuario;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\PermisoUsuario;
use App\Models\Tablas\UsuariosRoles;
use App\Models\Utilitarios\Correo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;

class DirectorProyectosController extends Controller
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
        $directores = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Usuarios_Roles.*', 'TBL_Roles.*')
            ->where('TBL_Roles.Id', '=', '2')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.id', 'ASC')
            ->get();
        return view('administrador.director.listar', compact('directores', 'datos', 'notificaciones', 'cantidad'));
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
        return view('administrador.director.crear', compact('datos', 'notificaciones', 'cantidad'));
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
        $director = Usuarios::obtenerUsuario($request['USR_Documento_Usuario']);
        UsuariosRoles::asignarRol(2, $director->id);
        MenuUsuario::asignarMenuDirector($director->id);
        PermisoUsuario::asignarPermisosDirector($director->id);
        Usuarios::enviarcorreo($request, 'Bienvenido a FacilPRY, Software de Gestión de Proyectos', 'Bienvenido ' . $request['USR_Nombres_Usuario'], 'general.correo.bienvenida');

        Notificaciones::crearNotificacion(
            'Hola! ' . $request->USR_Nombres_Usuario . ' ' . $request->USR_Apellidos_Usuario . ', Bienvenido a FacilPRY, verifique sus datos.',
            session()->get('Usuario_Id'),
            $director->id,
            'perfil',
            null,
            null,
            'account_circle'
        );

        return redirect()->back()->with('mensaje', 'Director de Proyectos agregado con exito, por favor que ' . $request['USR_Nombres_Usuario'] . ' ' . $request['USR_Apellidos_Usuario'] . ' revise su correo electrónico');
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
        $director = Usuarios::findOrFail($id);
        return view('administrador.director.editar', compact('director', 'datos', 'notificaciones', 'cantidad'));
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
            $request->USR_Nombres_Usuario . ' ' . $request->USR_Apellidos_Usuario . ', sus datos fueron actualizados',
            session()->get('Usuario_Id'),
            $id,
            'perfil',
            null,
            null,
            'update'
        );
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
        if ($request->ajax()) {
            try {
                Usuarios::destroy($id);
                return response()->json(['mensaje' => 'ok']);
            } catch (QueryException $e) {
                return response()->json(['mensaje' => 'ng']);
            }
        }
    }
}
