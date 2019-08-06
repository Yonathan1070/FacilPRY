<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    protected $table = "TBL_Empresas";
    protected $fillable = ['EMP_Nombre_Empresa',
        'EMP_NIT_Empresa',
        'EMP_Telefono_Empresa',
        'EMP_Direccion_Empresa',
        'EMP_Correo_Empresa',
        'EMP_Logo_Empresa'];
    protected $guarded = ['id'];
}
