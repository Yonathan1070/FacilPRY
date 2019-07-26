<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Iconos extends Model
{
    protected $table = "TBL_Iconos";
    protected $fillable = ['ICO_Icono'];
    protected $guarded = ['id'];
}
