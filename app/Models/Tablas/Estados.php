<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $table = "TBL_Estados";
    protected $fillable = ['EST_Nombre_Estado'];
    protected $guarded = ['id'];
}
