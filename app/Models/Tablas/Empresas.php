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

    //Función que obtiene las empresas activas
    public static function obtenerEmpresasActivas()
    {
        $empresas = DB::table('TBL_Empresas')
            ->where('EMP_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->where('EMP_Estado_Empresa', '=', 1)
            ->get();
        
        return $empresas;
    }

    //Función que obtiene las empresas inactivas
    public static function obtenerEmpresasInactivas()
    {
        $empresas = DB::table('TBL_Empresas')
            ->where('EMP_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->where('EMP_Estado_Empresa', '=', 0)
            ->get();
        
        return $empresas;
    }
    
    //Funcion que obtiene los datos de la empresa
    public static function obtenerDatosEmpresa()
    {
        $datos = DB::table('TBL_Usuarios as u')
            ->join('TBL_Empresas as e', 'u.USR_Empresa_Id', '=', 'e.id')
            ->where('u.id', '=', session()->get('Usuario_Id'))
            ->first();
        
        return $datos;
    }

    //Función que crea la empresa
    public static function crearEmpresa($request)
    {
        Empresas::create([
            'EMP_Nombre_Empresa' => $request['EMP_Nombre_Empresa'],
            'EMP_NIT_Empresa' => $request['EMP_NIT_Empresa'],
            'EMP_Telefono_Empresa' => $request['EMP_Telefono_Empresa'],
            'EMP_Direccion_Empresa' => $request['EMP_Direccion_Empresa'],
            'EMP_Correo_Empresa' => $request['EMP_Correo_Empresa'],
            'EMP_Logo_Empresa' => null,
            'EMP_Empresa_Id' => $request['id']
        ]);
    }

    //Función que actualiza los datos de la empresa
    public static function editarEmpresa($request, $id)
    {
        Empresas::findOrFail($id)
            ->update([
                'EMP_Nombre_Empresa' => $request['EMP_Nombre_Empresa'],
                'EMP_NIT_Empresa' => $request['EMP_NIT_Empresa'],
                'EMP_Telefono_Empresa' => $request['EMP_Telefono_Empresa'],
                'EMP_Direccion_Empresa' => $request['EMP_Direccion_Empresa'],
                'EMP_Correo_Empresa' => $request['EMP_Correo_Empresa']
            ]);
    }

    //Función que cambia el estado de la empresa
    public static function cambiarEstado($id)
    {
        Empresas::findOrFail($id)
            ->update([
                'EMP_Estado_Empresa' => 0
            ]);
    }

    //Función que cambia el estado a activo de la empresa
    public static function cambiarEstadoActivado($id)
    {
        Empresas::findOrFail($id)
            ->update([
                'EMP_Estado_Empresa' => 1
            ]);
    }
}
