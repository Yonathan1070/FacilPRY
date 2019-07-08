<?php

namespace App\Models\Tablas;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
    protected $remember_token = false;
    protected $table = 'TBL_Usuarios';
    protected $fillable = ['USR_Tipo_Documento',
        'USR_Documento',
        'USR_Nombre',
        'USR_Apellido',
        'USR_Fecha_Nacimiento',
        'USR_Direccion_Residencia',
        'USR_Telefono',
        'USR_Correo',
        'USR_Nombre_Usuario',
        'password'];
    protected $guarded = ['id'];
}
