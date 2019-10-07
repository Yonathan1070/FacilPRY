<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = "TBL_Permiso";
    protected $fillable = ['PRM_Nombre_Permiso',
        'PRM_Slug_Permiso'
    ];
    protected $guarded = ['id'];

    public function usuarios(){
        return $this->belongsToMany(Usuarios::class, 'TBL_Permiso_Usuario', 'PRM_USR_Usuario_Id', 'PRM_USR_Permiso_Id');
    }
}
