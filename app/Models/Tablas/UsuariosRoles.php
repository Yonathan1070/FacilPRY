<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class UsuariosRoles extends Model
{
    protected $table = "TBL_Usuarios_Roles";
    protected $fillable = ['USR_RLS_Rol_Id',
        'USR_RLS_Usuario_Id',
        'USR_RLS_Estado'];
    protected $timestamps = false;
}
