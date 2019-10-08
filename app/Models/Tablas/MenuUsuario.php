<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class MenuUsuario extends Model
{
    protected $table = "TBL_Menu_Usuario";
    protected $fillable = [
        'MN_USR_Usuario_Id',
        'MN_USR_Menu_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public static function asignarMenuDirector($id)
    {
        MenuUsuario::asignarInicioDirector($id);
        MenuUsuario::asignarPerfilOperacion($id);
        MenuUsuario::asignarDecisiones($id);
        MenuUsuario::asignarClientes($id);
        MenuUsuario::asignarProyectos($id);
        MenuUsuario::asignarRoles($id);
        MenuUsuario::asignarCobros($id);
    }

    public static function asignarInicioAdministrador($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 1
        ]);
    }
    public static function asignarMenu($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 2
        ]);
    }
    public static function asignarDirectorProyectos($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 3
        ]);
    }
    public static function asignarRoles($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 4
        ]);
    }
    public static function asignarPermisos($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 5
        ]);
    }
    public static function asignarInicioDirector($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 6
        ]);
    }
    public static function asignarPerfilOperacion($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 7
        ]);
    }
    public static function asignarClientes($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 8
        ]);
    }
    public static function asignarProyectos($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 9
        ]);
    }
    public static function asignarDecisiones($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 10
        ]);
    }
    public static function asignarCobros($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 11
        ]);
    }
    public static function asignarInicioPerfilOperacion($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 12
        ]);
    }
    public static function asignarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 13
        ]);
    }
    public static function asignarInicioTester($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 14
        ]);
    }
    public static function asignarInicioCliente($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 15
        ]);
    }
    public static function asignarAprobarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 16
        ]);
    }
    public static function asignarFinanzas($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 17
        ]);
    }
}
