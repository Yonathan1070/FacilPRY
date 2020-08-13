<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = "TBL_Publicacion";
    protected $fillable = ['PBL_Parrilla_Id',
        'PBL_Fecha',
        'PBL_Publico',
        'PBL_Copy_General',
        'PBL_Copy_Pieza',
        'PBL_Tipo',
        'PBL_Ubicacion',
        'PBL_Estado_Id'];
    protected $guarded = ['id'];
}
