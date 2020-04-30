<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Empresa, donde se establecen los atributos de la tabla en la 
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
class Empresas extends Model
{
    protected $table = "TBL_Empresas";
    protected $fillable = [
        'EMP_Nombre_Empresa',
        'EMP_NIT_Empresa',
        'EMP_Telefono_Empresa',
        'EMP_Direccion_Empresa',
        'EMP_Correo_Empresa',
        'EMP_Logo_Empresa',
        'EMP_Empresa_Id',
        'EMP_Estado_Empresa'
    ];
    protected $guarded = ['id'];

    #Función que obtiene las empresas activas
    public static function obtenerEmpresasActivas()
    {
        $empresas = DB::table('TBL_Usuarios as u')
            ->rightJoin(
                'TBL_Empresas as e',
                'e.id',
                '=',
                'u.USR_Empresa_Id'
            )->select(
                DB::raw('count(u.id) as clientes'),
                'e.*'
            )->where(
                'EMP_Empresa_Id', '=', session()->get('Empresa_Id')
            )->where(
                'EMP_Estado_Empresa', '=', 1
            )->groupBy(
                'e.id'
            )->get();
        
        return $empresas;
    }

    #Función que obtiene las empresas inactivas
    public static function obtenerEmpresasInactivas()
    {
        $empresas = DB::table('TBL_Empresas')
            ->where(
                'EMP_Empresa_Id', '=', session()->get('Empresa_Id')
            )->where(
                'EMP_Estado_Empresa', '=', 0
            )->get();
        
        return $empresas;
    }
    
    #Funcion que obtiene los datos de la empresa
    public static function obtenerDatosEmpresa($id)
    {
        $datos = DB::table('TBL_Usuarios as u')
            ->join(
                'TBL_Empresas as e',
                'u.USR_Empresa_Id',
                '=',
                'e.id'
            )->where(
                'u.id', '=', $id
            )->first();
        
        return $datos;
    }

    #Funcion para obtener id de la empresa
    public static function obtenerIdEmpresa($id)
    {
        $id_empresa = DB::table('TBL_Usuarios as uu')
            ->join(
                'TBL_Usuarios as ud',
                'uu.id',
                '=',
                'ud.USR_Supervisor_Id'
            )->select(
                'uu.USR_Empresa_Id'
            )->where(
                'ud.id', '=', $id
            )->first();
        
        return $id_empresa;
    }

    #Obtener Empresa por medio de proyecto
    public static function obtenerEmpresa()
    {
        $empresa = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'eu.EMP_Empresa_Id')
            ->select('ed.id')
            ->first();
        
        return $empresa;
    }

    #Función que crea la empresa
    public static function crearEmpresa($request)
    {
        Empresas::create([
            'EMP_Nombre_Empresa' => $request['EMP_Nombre_Empresa'],
            'EMP_NIT_Empresa' => $request['EMP_NIT_Empresa'],
            'EMP_Telefono_Empresa' => $request['EMP_Telefono_Empresa'],
            'EMP_Direccion_Empresa' => $request['EMP_Direccion_Empresa'],
            'EMP_Correo_Empresa' => $request['EMP_Correo_Empresa'],
            'EMP_Logo_Empresa' => null,
            'EMP_Empresa_Id' => $request['id'],
            'EMP_Estado_Empresa' => 1
        ]);

        LogCambios::guardar(
            'TBL_Empresas',
            'UPDATE',
            'Creó la empresa de la siguiente forma:'.
                ' EMP_Nombre_Empresa -> '.$request->EMP_Nombre_Empresa.
                ' EMP_NIT_Empresa -> '.$request->EMP_NIT_Empresa.
                ' EMP_Telefono_Empresa -> '.$request->EMP_Telefono_Empresa.
                ' EMP_Direccion_Empresa -> '.$request->EMP_Direccion_Empresa.
                ' EMP_Correo_Empresa -> '.$request->EMP_Correo_Empresa.
                ' EMP_Logo_Empresa -> NULL'.
                ' EMP_Empresa_Id -> '.$request->id.
                ', EMP_Estado_Empresa -> 1',
            session()->get('Usuario_Id')
        );
    }

    #Función que actualiza los datos de la empresa
    public static function editarEmpresa($request, $id)
    {
        $oldEmpresa = Empresas::findOrFail($id);
        $newEmpresa = $oldEmpresa;
        $newEmpresa->update([
            'EMP_Nombre_Empresa' => $request['EMP_Nombre_Empresa'],
            'EMP_NIT_Empresa' => $request['EMP_NIT_Empresa'],
            'EMP_Telefono_Empresa' => $request['EMP_Telefono_Empresa'],
            'EMP_Direccion_Empresa' => $request['EMP_Direccion_Empresa'],
            'EMP_Correo_Empresa' => $request['EMP_Correo_Empresa']
        ]);
        
        LogCambios::guardar(
            'TBL_Empresas',
            'UPDATE',
            'actualizó la empresa de la siguiente forma:'.
                ' EMP_Nombre_Empresa -> '.$oldEmpresa->EMP_Nombre_Empresa.' / '.$newEmpresa->EMP_Nombre_Empresa.
                ', EMP_NIT_Empresa -> '.$oldEmpresa->EMP_NIT_Empresa.' / '.$newEmpresa->EMP_NIT_Empresa.
                ', EMP_Telefono_Empresa -> '.$oldEmpresa->EMP_Telefono_Empresa.' / '.$newEmpresa->EMP_Telefono_Empresa.
                ', EMP_Direccion_Empresa -> '.$oldEmpresa->EMP_Direccion_Empresa.' / '.$newEmpresa->EMP_Direccion_Empresa.
                ', EMP_Correo_Empresa -> '.$oldEmpresa->EMP_Correo_Empresa.' / '.$newEmpresa->EMP_Correo_Empresa.
                ', EMP_Logo_Empresa -> '.$oldEmpresa->EMP_Logo_Empresa.' / '.$newEmpresa->EMP_Logo_Empresa.
                ', EMP_Empresa_Id -> '.$oldEmpresa->EMP_Empresa_Id.' / '.$newEmpresa->EMP_Empresa_Id.
                ', EMP_Estado_Empresa -> '.$oldEmpresa->EMP_Estado_Empresa.' / '.$newEmpresa->EMP_Estado_Empresa,
            session()->get('Usuario_Id')
        );
    }

    #Función que cambia el estado de la empresa
    public static function cambiarEstado($id)
    {
        Empresas::findOrFail($id)
            ->update([
                'EMP_Estado_Empresa' => 0
            ]);
    }

    #Función que cambia el estado a activo de la empresa
    public static function cambiarEstadoActivado($id)
    {
        Empresas::findOrFail($id)
            ->update([
                'EMP_Estado_Empresa' => 1
            ]);
    }

    #Función para actualizar el logo de la empresa
    public static function cambiarLogo($usuario, $nombreArchivo)
    {
        $oldEmpresa = Empresas::findOrFail($usuario->USR_Empresa_Id);
        $empresa = Empresas::findOrFail($usuario->USR_Empresa_Id);
        $empresa->update([
            'EMP_Logo_Empresa' => $nombreArchivo
        ]);

        LogCambios::guardar(
            'TBL_Empresas',
            'UPDATE',
            'Cambió el logo de la empresa:'.
                ' id -> '.$usuario->USR_Empresa_Id.
                ', EMP_Logo_Empresa -> '.$oldEmpresa->EMP_Logo_Empresa.' / '.$nombreArchivo,
            session()->get('Usuario_Id')
        );
    }

    #Función para actualizar los datos de la empresa
    public static function actualizar($request)
    {
        $oldEmpresa = Empresas::findOrFail($request->id);
        $empresa = Empresas::findOrFail($request->id);
        
        $empresa->update(
            $request->all()
        );
        
        LogCambios::guardar(
            'TBL_Empresas',
            'UPDATE',
            'Cambió los datos de la empresa:'.
                ' EMP_Nombre_Empresa -> '.$oldEmpresa->EMP_Nombre_Empresa.' / '.$empresa->EMP_Nombre_Empresa.
                ', EMP_NIT_Empresa -> '.$oldEmpresa->EMP_NIT_Empresa.' / '.$empresa->EMP_NIT_Empresa.
                ', EMP_Telefono_Empresa -> '.$oldEmpresa->EMP_Telefono_Empresa.' / '.$empresa->EMP_Telefono_Empresa.
                ', EMP_Direccion_Empresa -> '.$oldEmpresa->EMP_Direccion_Empresa.' / '.$empresa->EMP_Direccion_Empresa.
                ', EMP_Correo_Empresa -> '.$oldEmpresa->EMP_Correo_Empresa.' / '.$empresa->EMP_Correo_Empresa.
                ', EMP_Logo_Empresa -> '.$oldEmpresa->EMP_Logo_Empresa.' / '.$empresa->EMP_Logo_Empresa.
                ', EMP_Empresa_Id -> '.$oldEmpresa->EMP_Empresa_Id.' / '.$empresa->EMP_Empresa_Id.
                ', EMP_Estado_Empresa -> '.$oldEmpresa->EMP_Estado_Empresa.' / '.$empresa->EMP_Estado_Empresa,
            session()->get('Usuario_Id')
        );
    }
}
