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

    public static function asignarPermisosDirector($id){
        PermisoUsuario::asignarCrudDecisiones($id);
        PermisoUsuario::asignarCrudClientes($id);
        PermisoUsuario::asignarCRProyectos($id);
        PermisoUsuario::asignarCrudRoles($id);
        PermisoUsuario::asignarListarCobros($id);
        PermisoUsuario::asignarEditarPerfil($id);
    }

    public static function asignarCrudActividades($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 1
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 2
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 3
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 4
        ]);
    }
    public static function asignarCrudClientes($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 5
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 6
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 7
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 8
        ]);
    }
    public static function asignarListarCobros($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 9
        ]);
    }
    public static function asignarCrudDecisiones($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 10
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 11
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 12
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 13
        ]);
    }
    public static function asignarEditarPerfil($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 14
        ]);
    }
    public static function asignarCRProyectos($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 15
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 16
        ]);
    }
    public static function asignarCrudRequerimientos($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 17
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 18
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 19
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 20
        ]);
    }
    public static function asignarCrudRoles($id){
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 21
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 22
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 23
        ]);
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 24
        ]);
    }
}
