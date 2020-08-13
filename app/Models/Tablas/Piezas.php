<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Piezas extends Model
{
    protected $table = "TBL_Pieza";
    protected $fillable = ['PZA_Url',
        'PZA_Publicacion_Id'];
    protected $guarded = ['id'];
}
