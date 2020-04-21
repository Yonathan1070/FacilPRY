<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Roles, donde se haran las distintas consultas a la Base de 
 * datos sobre la tabla Roles
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Roles extends Model
{
    protected $table = "TBL_Roles";
    protected $fillable = ['RLS_Rol_Id', 
        'RLS_Nombre_Rol', 
        'RLS_Descripcion_Rol', 
        'RLS_Empresa_Id'
    ];
    protected $guarded = ['id'];

    #Función para obtener los roles a excepción de Cliente
    public static function obtenerRolesNoCliente()
    {
        $roles = Roles::where('id', '!=', 4)
            ->where(
                'RLS_Nombre_Rol', '<>', 'Cliente'
            )->get();
        
        return $roles;
    }

    #Función que obtiene los roles asignados
    public static function obtenerRolesAsignados($id){
        $rolesAsignados = DB::table('TBL_Roles as r')
            ->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Rol_Id',
                '=',
                'r.id'
            )->select(
                'r.*'
            )->where(
                'ur.USR_RLS_Usuario_Id', '=', $id
            )->where(
                'r.RLS_Nombre_Rol', '<>', 'Cliente'
            )->get();
        
        return $rolesAsignados;
    }

    #Funcion para obtener los roles asociados a perfil de operación
    public static function obtenerRolesPefilOperacion()
    {
        $roles = Roles::where('id', '<>', '4')
            ->where('RLS_Rol_Id', '=', 4)
            ->orderBy('id')
            ->get();
        
        return $roles;
    }
}
