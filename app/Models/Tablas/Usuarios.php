<?php

namespace App\Models\Tablas;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class Usuarios extends Authenticatable
{
    protected $remember_token = false;
    protected $table = 'TBL_Usuarios';
    protected $fillable = ['USR_Tipo_Documento',
        'USR_Documento',
        'USR_Nombre',
        'USR_Apellido',
        'USR_Fecha_Nacimiento',
        'USR_Direccion_Residencia',
        'USR_Telefono',
        'USR_Correo',
        'USR_Nombre_Usuario',
        'password'];
    protected $guarded = ['id'];
    
    public function roles(){
        return $this->belongsToMany(Roles::class, 'TBL_Usuarios_Roles', 'USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id')->withPivot('USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id');
    }

    public function setSession($roles){
        if (count($roles) == 1) {
            Session::put([
                'Rol_Id' => $roles[0]['id'],
                'Rol_Nombre' => $roles[0]['RLS_Nombre'],
                'Usuario_Nombre' => $this->USR_Nombre_Usuario,
                'Usuario_Id' => $this->id,
                'Usuario_Nombre_Completo' => $this->USR_Nombre.' '.$this->USR_Apellido,
                'Usuario_Correo' => $this->USR_Correo
            ]);
        }
    }
}
