<?php

namespace App\Models\Tablas;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;

class Usuarios extends Authenticatable
{
    protected $remember_token = false;
    protected $table = 'TBL_Usuarios';
    protected $fillable = ['USR_Tipo_Documento_Usuario',
        'USR_Documento_Usuario',
        'USR_Nombres_Usuario',
        'USR_Apellidos_Usuario',
        'USR_Fecha_Nacimiento_Usuario',
        'USR_Direccion_Residencia_Usuario',
        'USR_Telefono_Usuario',
        'USR_Correo_Usuario',
        'USR_Nombre_Usuario',
        'password',
        'USR_Foto_Perfil_Usuario',
        'USR_Supervisor_Id',
        'USR_Empresa_Id',
        'USR_Costo_Hora'];
    protected $guarded = ['id'];
    
    public function roles(){
        return $this->belongsToMany(Roles::class, 'TBL_Usuarios_Roles', 'USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id')->withPivot('USR_RLS_Usuario_Id', 'USR_RLS_Rol_Id');
    }

    public function setSession($roles){
        if (count($roles) == 1) {
            Session::put([
                'Sub_Rol_Id' => $roles[0]['RLS_Rol_Id'],
                'Rol_Id' => $roles[0]['id'],
                'Rol_Nombre' => $roles[0]['RLS_Nombre_Rol'],
                'Usuario_Id' => $this->id,
                'Empresa_Id' => $this->USR_Empresa_Id
            ]);
        }
    }
}
