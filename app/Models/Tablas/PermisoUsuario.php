<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo PermisoUsuario, donde se establecen los atributos de la tabla en la 
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
class PermisoUsuario extends Model
{
    protected $table = "TBL_Permiso_Usuario";
    protected $fillable = [
        'PRM_USR_Usuario_Id',
        'PRM_USR_Permiso_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    #Función que asigna los permisos al Director de proyectos
    public static function asignarPermisosDirector($id)
    {
        PermisoUsuario::asignarCrudDecisiones($id);
        PermisoUsuario::asignarCrudClientes($id);
        PermisoUsuario::asignarCRProyectos($id);
        PermisoUsuario::asignarCrudRoles($id);
        PermisoUsuario::asignarListarCobros($id);
        PermisoUsuario::asignarEditarPerfil($id);
        PermisoUsuario::asignarCrudActividades($id);
        PermisoUsuario::asignarCrudEmpresas($id);
        PermisoUsuario::asignarCrudRequerimientos($id);
        PermisoUsuario::asignarCrudPerfilOperacion($id);
    }

    #Función que asigna los permisos al Perfil de Operación
    public static function asignarPermisosPerfilOperacion($id)
    {
        PermisoUsuario::asignarListarEmpresas($id);
        PermisoUsuario::asignarListarProyectos($id);
        PermisoUsuario::asignarListarRequerimientos($id);
    }

    #Funcion que asigna el permiso para editar perfil
    public static function asignarPermisoPerfil($id)
    {
        PermisoUsuario::asignarEditarPerfil($id);
    }

    #Funcion que asigna los permisos al cliente
    public static function asignarPermisosCliente($id)
    {
        PermisoUsuario::asignarListarRequerimientos($id);
        PermisoUsuario::asignarListarProyectos($id);
        PermisoUsuario::asignarListarActividades($id);
    }

    #Funciones de asignación
    public static function asignarCrudActividades($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 1
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó el permiso listar actividades:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 1',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 2
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó el permiso crear actividades trabajador:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 2',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 3
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó el permiso crear actividades cliente:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 3',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 4
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó el permiso editar actividades:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 4',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 5
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar actividades:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 5',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarListarActividades($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 1
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar actividades:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 1',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudClientes($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 6
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar clientes:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 6',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 7
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear clientes:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 7',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 8
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar clientes:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 8',
            session()->get('Usuario_Id')
        );
        
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 9
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar clientes:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 9',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudEmpresas($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 26
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar empresas:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 26',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 27
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear empresas:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 27',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 28
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar empresas:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 28',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 29
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar empresas:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 29',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarListarEmpresas($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 26
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar empresas:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 26',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarListarCobros($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 10
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar cobros:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 10',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudDecisiones($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 11
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar decisiones:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 11',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 12
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear decisiones:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 12',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 13
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar decisiones:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 13',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 14
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar decisiones:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 14',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarEditarPerfil($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 15
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar perfil:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 15',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCRProyectos($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 16
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar proyectos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 16',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 17
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear proyectos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 17',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarListarProyectos($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 16
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar proyectos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 16',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudRequerimientos($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 18
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar requerimientos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 18',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 19
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear requerimientos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 19',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 20
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar requerimientos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 20',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 21
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar requerimientos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 21',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarListarRequerimientos($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 18
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar requerimientos:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 18',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudRoles($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 22
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar roles:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 22',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 23
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear roles:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 23',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 24
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar roles:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 24',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 25
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar roles:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 25',
            session()->get('Usuario_Id')
        );
    }
    public static function asignarCrudPerfilOperacion($id)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 31
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso listar perfil operacion:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 31',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 32
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso crear perfil de operación:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 32',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 33
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso editar perfil de operación:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 33',
            session()->get('Usuario_Id')
        );

        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => 34
        ]);
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permiso eliminar perfil de operación:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> 34',
            session()->get('Usuario_Id')
        );
    }

    #función para asignar permisos
    public static function asignar($id, $menuId)
    {
        PermisoUsuario::create([
            'PRM_USR_Usuario_Id' => $id,
            'PRM_USR_Permiso_Id' => $menuId
        ]);
        
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'INSERT',
            'Asignó permisos al usuario:'.
                ' PRM_USR_Usuario_Id -> '.$id.
                ', PRM_USR_Permiso_Id -> '.$menuId,
            session()->get('Usuario_Id')
        );
    }
    
    #función para desasignar permisos
    public static function desasignar($asignado)
    {
        $oldAsignado = $asignado;
        $asignado->delete();
        
        LogCambios::guardar(
            'TBL_Permiso_Usuario',
            'DELETE',
            'Desasignó permisos al usuario:'.
                ' PRM_USR_Usuario_Id -> '.$oldAsignado->PRM_USR_Usuario_Id.
                ', PRM_USR_Permiso_Id -> '.$oldAsignado->PRM_USR_Permiso_Id,
            session()->get('Usuario_Id')
        );
    }
}
