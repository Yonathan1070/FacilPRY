<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\UsuariosRoles;
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $directores = DB::table('TBL_Usuarios')
            ->join('TBL_Usuarios_Roles', 'TBL_Usuarios.id', '=', 'TBL_Usuarios_Roles.USR_RLS_Usuario_Id')
            ->join('TBL_Roles', 'TBL_Usuarios_Roles.USR_RLS_Rol_Id', '=', 'TBL_Roles.Id')
            ->select('TBL_Usuarios.*', 'TBL_Usuarios_Roles.*', 'TBL_Roles.*')
            ->where('TBL_Roles.Id', '=', '2')
            ->where('TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1')
            ->orderBy('TBL_Usuarios.id', 'ASC')
            ->get();
        return view('administrador.director.listar', compact('directores', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('administrador.director.crear', compact('datos'));
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
            'USR_Empresa_Id' => session()->get('Empresa_Id')
        ]);
        $director = Usuarios::where('USR_Documento_Usuario', '=', $request['USR_Documento_Usuario'])->first();
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => 2,
            'USR_RLS_Usuario_Id' => $director->id,
            'USR_RLS_Estado' => 1
        ]);

        Mail::send('general.correo.bienvenida', [
            'nombre' => $request['USR_Nombres_Usuario'].' '.$request['USR_Apellidos_Usuario'],
            'username' => $request['USR_Nombre_Usuario']], function($message) use ($request){
            $message->from('8076cdda3e-9b8334@inbox.mailtrap.io', 'FacilPRY');
            $message->to($request['USR_Correo_Usuario'], 'Bienvenido a FacilPRY, Software de Gestión de Proyectos')
                ->subject('Bienvenido '.$request['USR_Nombres_Usuario']);
        });
        if (Mail::failures()) {
            return redirect()->back()->withErrors('Error al envíar el correo.');
        }
        return redirect()->back()->with('mensaje', 'Director de Proyectos agregado con exito, por favor que '.$request['USR_Nombres_Usuario'].' '.$request['USR_Apellidos_Usuario'].' revise su correo electrónico');
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
        $director = Usuarios::findOrFail($id);
        return view('administrador.director.editar', compact('director', 'datos'));
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
        Usuarios::findOrFail($id)->update([
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => $request['USR_Direccion_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
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
            return redirect()->back()->with('mensaje', 'Director de proyectos eliminado satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->back()->withErrors(['Director de Proyectos está siendo usado por otro recurso.']);
        }
    }
}
