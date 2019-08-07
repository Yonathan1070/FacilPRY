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
        'password',
        'USR_Foto_Perfil',
        'USR_Empresa_Id'];
    protected $guarded = ['id'];
    
    public function roles(){
        return $this->belongsToMany(Roles::class, 'TBL_Usuarios_Roles', 'USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id')->withPivot('USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id');
    }

    public function setSession($roles){
        if (count($roles) == 1) {
            Session::put([
                'Rol_Id' => $roles[0]['id'],
                'Rol_Nombre' => $roles[0]['RLS_Nombre'],
                'Usuario_Documento' => $this->USR_Documento,
                'Usuario_Nombre' => $this->USR_Nombre_Usuario,
                'Usuario_Id' => $this->id,
                'Usuario_Nom' => $this->USR_Nombre,
                'Usuario_Apellido' => $this->USR_Apellido,
                'Usuario_Nombre_Completo' => $this->USR_Nombre.' '.$this->USR_Apellido,
                'Usuario_Direccion_Residencia' => $this->USR_Direccion_Residencia,
                'Usuario_Telefono' => $this->USR_Telefono,
                'Usuario_Correo' => $this->USR_Correo,
                'Foto_Perfil' => $this->USR_Foto_Perfil,
            ]);
        }
    }
}
