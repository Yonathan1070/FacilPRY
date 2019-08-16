<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model
{
    protected $table = "TBL_Calificaciones";
    protected $fillable = ['CALIF_Calificaion',
        'CALIF_Trabajador_Id',
        'CALIF_Decision_Id',
        'CALIF_Fecha_Calificacion'];
    protected $guarded = ['id'];
}
