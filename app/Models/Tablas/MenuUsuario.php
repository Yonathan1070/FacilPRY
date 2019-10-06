<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class MenuUsuario extends Model
{
    protected $table = "TBL_Menu_Usuario";
    protected $fillable = ['MN_USR_Usuario_Id',
        'MN_USR_Menu_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;
}
