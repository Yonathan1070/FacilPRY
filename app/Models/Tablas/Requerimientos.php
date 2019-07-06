<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Requerimientos extends Model
{
    protected $table = "TBL_Requerimientos";
    protected $fillable = ['REQ_Nombre_Requerimiento',
        'REQ_Descripcion_Requerimiento',
        'REQ_Proyecto_Id'];
    protected $guarded = ['id'];
}
