<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class SesionUsuario extends Model
{
    protected $table = "TBL_Sesion_Usuario";
    protected $fillable = [
        'SES_USR_Fecha_Sesion',
        'SES_USR_Estado_Sesion',
        'SES_USR_Usuario_Id'
    ];
    protected $guarded = ['id'];

    public static function obtenerSesiones()
    {
        $sesiones = SesionUsuario::rightJoin('TBL_Usuarios as u', 'u.id', '=', 'TBL_Sesion_Usuario.SES_USR_Usuario_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('r.RLS_Rol_Id', '<>', 3)
            ->select(
                'r.*',
                'TBL_Sesion_Usuario.*',
                'u.*'
            )
            ->groupBy('u.id')
            ->get();
        
        return $sesiones;
    }
}
