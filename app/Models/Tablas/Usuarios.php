<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = "TBL_Usuarios";
    protected $fillable = ['USR_Tipo_Documento',
        'USR_Documento',
        'USR_Nombre',
        'USR_Apellido',
        'USR_Fecha_Nacimiento',
        'USR_Direccion_Residencia',
        'USR_Telefono',
        'USR_Correo',
        'USR_Nombre_Usuario',
        'USR_Clave_Usuario'];
    protected $guarded = ['id'];
}
