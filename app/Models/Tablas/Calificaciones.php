<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model
{
    protected $table = "TBL_Calificaciones";
    protected $fillable = ['CALIF_Calificaion',
        'CALIF_Indicador_Id',
        'CALIF_Usuario_Id',
        'CALIF_Proyecto_Id'];
    protected $guarded = ['id'];
}
