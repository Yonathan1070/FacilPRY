<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Parrilla extends Model
{
    protected $table = "TBL_Parrilla";
    protected $fillable = ['PRL_Mes',
        'PRL_Anio',
        'PRL_Proyecto_Id'];
    protected $guarded = ['id'];
}
