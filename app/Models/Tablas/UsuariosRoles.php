<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo UsuariosRoles, donde se establecen los atributos de la tabla en la 
 * Base de Datos y se realizan las distintas operaciones sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class UsuariosRoles extends Model
{
    protected $table = "TBL_Usuarios_Roles";
    protected $fillable = ['USR_RLS_Rol_Id',
        'USR_RLS_Usuario_Id',
        'USR_RLS_Estado'
    ];
    public $timestamps = false;

    #Funcion donde se asigna el rol
    public static function asignarRol($rolId, $usuarioId){
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => $rolId,
            'USR_RLS_Usuario_Id' => $usuarioId,
            'USR_RLS_Estado' => 1
        ]);

        LogCambios::guardar(
            'TBL_Usuarios_Roles',
            'INSERT',
            'Agignó el rol al usuario:'.
                ' USR_RLS_Rol_Id -> '.$rolId.
                ', USR_RLS_Usuario_Id -> '.$usuarioId.
                ', USR_RLS_Estado -> 1',
            session()->get('Usuario_Id')
        );
    }

    #Función que deja inactivo el rol usuario
    public static function inactivarUsuario($id, $idRol)
    {
        UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
            ->where('USR_RLS_Rol_Id', '=', $idRol)
            ->update(['USR_RLS_Estado' => 0]);
        
        LogCambios::guardar(
            'TBL_Usuarios_Roles',
            'UPDATE',
            'Inactiva el rol del usuario:'.
                ' USR_RLS_Rol_Id -> '.$idRol.
                ', USR_RLS_Usuario_Id -> '.$id.
                ', USR_RLS_Estado -> 0',
            session()->get('Usuario_Id')
        );
    }

    #Función que reactiva el rol del usuario
    public static function activarUsuario($id, $idRol)
    {
        UsuariosRoles::where('USR_RLS_Usuario_Id', '=', $id)
            ->where('USR_RLS_Rol_Id', '=', $idRol)
            ->update(['USR_RLS_Estado' => 1]);
        
        LogCambios::guardar(
            'TBL_Usuarios_Roles',
            'UPDATE',
            'Activa el rol del usuario:'.
                ' USR_RLS_Rol_Id -> '.$idRol.
                ', USR_RLS_Usuario_Id -> '.$id.
                ', USR_RLS_Estado -> 1',
            session()->get('Usuario_Id')
        );
    }

    #Función para asignar roles
    public static function desasignar($asignado)
    {
        $oldAsignado = $asignado;
        $asignado->delete();

        LogCambios::guardar(
            'TBL_Usuarios_Roles',
            'DELETE',
            'Desasignó el rol al usuario:'.
                ' USR_RLS_Rol_Id -> '.$oldAsignado->USR_RLS_Rol_Id.
                ', USR_RLS_Usuario_Id -> '.$oldAsignado->USR_RLS_Usuario_Id,
            session()->get('Usuario_Id')
        );
    }
}
