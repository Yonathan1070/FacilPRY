<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    protected $table = "TBL_Permiso_Usuario";
    protected $fillable = ['PRM_USR_Usuario_Id',
        'PRM_USR_Permiso_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;
}
