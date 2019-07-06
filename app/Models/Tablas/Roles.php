<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "TBL_Roles";
    protected $fillable = ['RLS_Nombre', 'RLS_Descripcion'];
    protected $guarded = ['id'];
}
