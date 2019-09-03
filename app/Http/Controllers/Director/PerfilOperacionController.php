<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\UsuariosRoles;
use Illuminate\Database\QueryException;
use App\Models\Tablas\Roles;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class PerfilOperacionController extends Controller
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
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'u.id', '=', 'ur.USR_RLS_Usuario_Id')
            ->join('TBL_Roles as r', 'ur.USR_RLS_Rol_Id', '=', 'r.Id')
            ->select('u.*', 'r.RLS_Nombre_Rol')
            ->where('r.RLS_Rol_Id', '=', '6')
            ->where('ur.USR_RLS_Estado', '=', '1')
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
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $roles = Roles::where('RLS_Rol_Id', '=', 6)->orderBy('id')->get();
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
        $perfil = Usuarios::where("USR_Documento_Usuario","=",$request['USR_Documento_Usuario'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => $request['USR_RLS_Rol_Id'],
            'USR_RLS_Usuario_Id' => $perfil->id,
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::orderByDesc('created_at')->first();
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha creado el usuario '.$request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'person_add'
        );
        Notificaciones::crearNotificacion(
            'Hola! '.$request->USR_Nombres_Usuario.' '.$request->USR_Apellidos_Usuario.', Bienvenido a FacilPRY, verifique sus datos.',
            session()->get('Usuario_Id'),
            $datosU->id,
            'perfil_perfil_operacion',
            null,
            null,
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha actualizado los datos de '.$request->USR_Nombres_Usuario,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            null,
            null,
            null,
            'update'
        );
        Notificaciones::crearNotificacion(
            $request->USR_Nombres_Usuario.' '.$request->USR_Apellidos_Usuario.', sus datos fueron actualizados',
            session()->get('Usuario_Id'),
            $id,
            'perfil_perfil_operacion',
            null,
            null,
            'update'
        );
        Usuarios::findOrFail($id)->update($request->all());
        return redirect()->route('perfil_operacion_director')->with('mensaje', 'Perfi de operación  actualizado con exito');
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
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $datosU = Usuarios::findOrFail($id);
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha eliminado al usuario '.$datosU->USR_Nombres_Usuario,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                null,
                null,
                null,
                'delete_forever'
            );
            Usuarios::destroy($id);
            return redirect()->back()->with('mensaje', 'El Perfil de operación fue eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->back()->withErrors(['El Perfil de operación está siendo usado por otro recurso.']);
        }
    }
}
