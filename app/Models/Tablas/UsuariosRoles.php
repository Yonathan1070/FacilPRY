<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class UsuariosRoles extends Model
{
    protected $table = "TBL_Usuarios_Roles";
    protected $fillable = ['USR_RLS_Rol_Id',
        'USR_RLS_Usuario_Id',
        'USR_RLS_Estado'];
    public $timestamps = false;

    public static function asignarRol($rolId, $usuarioId){
        UsuariosRoles::create([
            'USR_RLS_Rol_Id' => $rolId,
            'USR_RLS_Usuario_Id' => $usuarioId,
            'USR_RLS_Estado' => 1
        ]);
    }
}
