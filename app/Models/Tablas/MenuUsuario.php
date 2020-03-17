<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo MenuUsuario, donde se establecen los atributos de la tabla en la 
 * Base de Datos y se realizan las distintas operaciones sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class MenuUsuario extends Model
{
    protected $table = "TBL_Menu_Usuario";
    protected $fillable = [
        'MN_USR_Usuario_Id',
        'MN_USR_Menu_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    #Función que asigna los items al director de proyectos
    public static function asignarMenuDirector($id)
    {
        //MenuUsuario::asignarInicioDirector($id);
        MenuUsuario::asignarPerfilOperacion($id);
        MenuUsuario::asignarDecisiones($id);
        MenuUsuario::asignarEmpresas($id);
        MenuUsuario::asignarRoles($id);
        MenuUsuario::asignarCobros($id);
    }

    #Función que asigna los items al perfil de operación
    public static function asignarMenuPerfilOperacion($id)
    {
        //MenuUsuario::asignarInicioPerfilOperacion($id);
        //MenuUsuario::asignarActividades($id);
        MenuUsuario::asignarEmpresas($id);
    }

    #Función que asigna los items al cliente 
    public static function asignarMenuCliente($id)
    {
        //MenuUsuario::asignarInicioCliente($id);
        //MenuUsuario::asignarAprobarActividades($id);
    }

    #Funciones de asignación de items
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
    public static function asignarEmpresas($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 8
        ]);
    }
    public static function asignarDecisiones($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 9
        ]);
    }
    public static function asignarCobros($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 10
        ]);
    }
    public static function asignarInicioPerfilOperacion($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 11
        ]);
    }
    public static function asignarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 12
        ]);
    }
    public static function asignarInicioTester($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 13
        ]);
    }
    public static function asignarInicioCliente($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 14
        ]);
    }
    public static function asignarAprobarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 15
        ]);
    }
    public static function asignarFinanzas($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 16
        ]);
    }
}
