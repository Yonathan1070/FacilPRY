<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = "TBL_Comentario";
    protected $fillable = ['CMR_Publicacion_Id',
        'CMR_Estado_Id',
        'CMR_Usuario_Id',
        'CMR_Comentario'];
    protected $guarded = ['id'];
}