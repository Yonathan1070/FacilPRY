<?php

namespace App\Models\Tablas;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

/**
 * Usuarios, modelo donde encontramos los atributos de la tabla
 * usuarios de la Base de Datos, adicional establece la sesión
 * cuando un usuario se ha logueado
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Usuarios extends Authenticatable
{
    protected $remember_token = false;
    protected $table = 'TBL_Usuarios';
    protected $fillable = ['USR_Tipo_Documento_Usuario',
        'USR_Documento_Usuario',
        'USR_Nombres_Usuario',
        'USR_Apellidos_Usuario',
        'USR_Fecha_Nacimiento_Usuario',
        'USR_Direccion_Residencia_Usuario',
        'USR_Telefono_Usuario',
        'USR_Correo_Usuario',
        'USR_Nombre_Usuario',
        'password',
        'USR_Foto_Perfil_Usuario',
        'USR_Supervisor_Id',
        'USR_Empresa_Id',
        'USR_Costo_Hora'
    ];
    protected $guarded = ['id'];
    
    #Funcion que obtiene los roles del usuario
    public function roles()
    {
        return $this->belongsToMany(
            Roles::class,
            'TBL_Usuarios_Roles',
            'USR_RLS_Usuario_Id',
            'USR_RLS_Rol_Id'
        )->withPivot('USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id');
    }

    #Funcion que establece la variable de sesión del usuario autenticado
    public function setSession($roles)
    {
        Session::put([
            'Usuario_Id' => $this->id,
            'Empresa_Id' => $this->USR_Empresa_Id
        ]);
        if (count($roles) == 1) {
            Session::put([
                'Rol_Id' => $roles[0]['id'],
                'Rol_Nombre' => $roles[0]['RLS_Nombre_Rol'],
                'Sub_Rol_Id' => $roles[0]['RLS_Rol_Id']
            ]);
        } else {
            Session::put([
                'Sub_Rol_Id' => $roles[0]['RLS_Rol_Id'],
                'roles' => $roles
            ]);
        }
    }

    #Funcion para obtener el cliente por proyecto
    public static function obtenerClienteProyecto($idP)
    {
        $datosU = DB::table('TBL_Proyectos as p')
            ->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->where(
                'p.id', '=', $idP
            )->first();
        
        return $datosU;
    }

    #Funcion para obtener el trabajador asociado a la actividad
    public static function obtenerPerfilAsociado($id)
    {
        $trabajador = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->where(
                'a.id', '=', $id
            )->first();
        
        return $trabajador;
    }

    #Funcion que obtiene los usuarios con el rol de perfil de operación
    public static function obtenerTrabajadores()
    {
        $trabajadores = DB::table('TBL_Usuarios as u')
            ->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->select(
                'u.*'
            )->where(
                'r.RLS_Nombre_Rol', '<>', 'Administrador'
            )->where(
                'r.RLS_Nombre_Rol', '<>', 'Director de Proyectos'
            )->where(
                'r.RLS_Nombre_Rol', '<>', 'Cliente'
            )->get();
        
        return $trabajadores;
    }

    #Función que obtiene los perfil de operación
    public static function obtenerPerfilOperacion()
    {
        $perfilesOperacion = DB::table('TBL_Usuarios as u')
            ->join(
                'TBL_Usuarios_Roles as ur',
                'u.id',
                '=',
                'ur.USR_RLS_Usuario_Id'
            )->join(
                'TBL_Roles as r',
                'ur.USR_RLS_Rol_Id',
                '=',
                'r.Id'
            )->select(
                'u.*',
                'ur.*',
                'r.RLS_Nombre_Rol',
                'u.id as Id_Perfil'
            )->where(
                'r.RLS_Rol_Id', '=', '4'
            )->where(
                'ur.USR_RLS_Estado', '=', '1'
            )->orderBy(
                'u.USR_Apellidos_Usuario', 'ASC'
            )->get();
        
        return $perfilesOperacion;
    }

    #Función que obtiene los directores de proyectos
    public static function obtenerDirectores()
    {
        $directores = DB::table('TBL_Usuarios')
            ->join(
                'TBL_Usuarios_Roles',
                'TBL_Usuarios.id',
                '=',
                'TBL_Usuarios_Roles.USR_RLS_Usuario_Id'
            )
            ->join(
                'TBL_Roles',
                'TBL_Usuarios_Roles.USR_RLS_Rol_Id',
                '=',
                'TBL_Roles.Id'
            )->select(
                'TBL_Usuarios.*',
                'TBL_Usuarios_Roles.*',
                'TBL_Roles.*'
            )->where(
                'TBL_Roles.Id', '=', '2'
            )->where(
                'TBL_Usuarios_Roles.USR_RLS_Estado', '=', '1'
            )->orderBy(
                'TBL_Usuarios.id', 'ASC'
            )->get();
        
        return $directores;
    }

    #Función para obtener el listado de clientes
    public static function obtenerClientes($id)
    {
        $clientes = DB::table('TBL_Usuarios as uu')
            ->join(
                'TBL_Usuarios as ud',
                'ud.id',
                '=',
                'uu.USR_Supervisor_Id'
            )->join(
                'TBL_Empresas as eu',
                'eu.id',
                '=',
                'uu.USR_Empresa_Id'
            )->join(
                'TBL_Empresas as ed',
                'ed.id',
                '=',
                'ud.USR_Empresa_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'uu.id',
                '=',
                'ur.USR_RLS_Usuario_Id'
            )->join(
                'TBL_Roles as r',
                'ur.USR_RLS_Rol_Id',
                '=',
                'r.Id'
            )->select(
                'uu.*', 'r.RLS_Nombre_Rol'
            )->where(
                'ur.USR_RLS_Rol_Id', '=', '3'
            )->where(
                'ur.USR_RLS_Estado', '=', '1'
            )->where(
                'eu.id', '=', $id
            )->get();
        
        return $clientes;
    }

    #Función para obtener el perfil de operación por actividad
    public static function obtenerPerfilOperacionActividad($id)
    {
        $perfil = DB::table('TBL_Usuarios as u')
            ->join(
                'TBL_Actividades as a',
                'a.ACT_Trabajador_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Roles as ro',
                'ro.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->where(
                'a.id', '=', $id
            )->first();
        
        return $perfil;
    }

    #Funcion que obtiene un usuario en específico
    public static function obtenerUsuario($documento)
    {
        return Usuarios::where(
            'USR_Documento_Usuario', '=', $documento
        )->first();
    }

    #Funcion que obtiene un usuario en específico por id
    public static function obtenerUsuarioById($id)
    {
        return Usuarios::findOrFail($id);
    }

    #Funcion para obtener los clientes
    public static function obtenerTodosClientes()
    {
        $clientes = DB::table('TBL_Usuarios as u')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('r.id', '=', 3)
            ->select('u.*')
            ->get();
        
        return $clientes;
    } 

    #Funcion que guarda el nuevo usuario en la Base de Datos
    public static function crearUsuario($request)
    {
        if($request['USR_Costo_Hora'] == null) {
            $request['USR_Costo_Hora'] = 0;
        }
        if($request->id == null) {
            $request->id = session()->get('Empresa_Id');
        }
        Usuarios::create([
            'USR_Tipo_Documento_Usuario' => $request['USR_Tipo_Documento_Usuario'],
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => 
                $request['USR_Direccion_Residencia_Usuario'].
                " ".
                $request['USR_Ciudad_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'password' => bcrypt($request['USR_Nombre_Usuario']),
            'USR_Supervisor_Id' => session()->get('Usuario_Id'),
            'USR_Empresa_Id' => $request->id,
            'USR_Costo_Hora' => $request['USR_Costo_Hora']
        ]);
    }

    #Funcion que actualiza los datos del usuario en la Base de Datos
    public static function editarUsuario($request, $id)
    {
        Usuarios::findOrFail($id)->update([
            'USR_Documento_Usuario' => $request['USR_Documento_Usuario'],
            'USR_Nombres_Usuario' => $request['USR_Nombres_Usuario'],
            'USR_Apellidos_Usuario' => $request['USR_Apellidos_Usuario'],
            'USR_Fecha_Nacimiento_Usuario' => $request['USR_Fecha_Nacimiento_Usuario'],
            'USR_Direccion_Residencia_Usuario' => 
                $request['USR_Direccion_Residencia_Usuario'],
            'USR_Telefono_Usuario' => $request['USR_Telefono_Usuario'],
            'USR_Correo_Usuario' => $request['USR_Correo_Usuario'],
            'USR_Nombre_Usuario' => $request['USR_Nombre_Usuario'],
            'USR_Costo_Hora' => $request['USR_Costo_Hora']
        ]);
    }

    #Funcion que envía el correo al usuario
    public static function enviarcorreo($request, $mensaje, $asunto, $plantilla)
    {
        Mail::send($plantilla, [
            'nombre' =>
                $request['USR_Nombres_Usuario'].' '.$request['USR_Apellidos_Usuario'],
            'username' =>
                $request['USR_Nombre_Usuario']],
                function($message) use ($request, $mensaje, $asunto){
            $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
            $message->to($request['USR_Correo_Usuario'], $mensaje)
                ->subject($asunto);
        });
    }
}
