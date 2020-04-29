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
        //MenuUsuario::asignarCobros($id);
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

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item inicio administrador del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 1',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarMenu($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 2
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 2',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarDirectorProyectos($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 3
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item director de proyectos del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 3',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarRoles($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 4
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item roles del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 4',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarPermisos($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 5
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item permisos del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 5',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarInicioDirector($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 6
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item inicio director del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 6',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarPerfilOperacion($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 7
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item perfil de operación del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 7',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarEmpresas($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 8
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item empresas del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 8',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarDecisiones($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 9
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item decisiones del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 9',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCobros($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 10
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item cobros del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 10',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarInicioPerfilOperacion($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 11
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item inicio perfil de operación del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 11',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 12
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item actividades del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 12',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarInicioTester($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 13
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item inicio tester del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 13',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarInicioCliente($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 14
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item inicio cliente del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 14',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarAprobarActividades($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 15
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item aprobar actividades del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 15',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarFinanzas($id)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $id,
            'MN_USR_Menu_Id' => 16
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item finanzas del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$id.
                ', MN_USR_Menu_Id -> 16',
            session()->get('Usuario_Id')
        );
    }

    #función para asignar un menú
    public static function asignar($request)
    {
        MenuUsuario::create([
            'MN_USR_Usuario_Id' => $request->id,
            'MN_USR_Menu_Id' => $request->menuId
        ]);

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'INSERT',
            'Asignó el item del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$request->id.
                ', MN_USR_Menu_Id -> '.$request->menuId,
            session()->get('Usuario_Id')
        );
    }

    #función para desasignar un menú
    public static function desasignar($asignado)
    {
        $oldAsignado = $asignado;
        $asignado->delete();

        LogCambios::guardar(
            'TBL_Menu_Usuario',
            'DELETE',
            'Desasignó el item del menú al usuario:'.
                ' MN_USR_Usuario_Id -> '.$oldAsignado->MN_USR_Usuario_Id.
                ', MN_USR_Menu_Id -> '.$oldAsignado->MN_USR_Menu_Id,
            session()->get('Usuario_Id')
        );
    }
}
