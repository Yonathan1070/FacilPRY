<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    protected $table = "TBL_Empresas";
    protected $fillable = ['EMP_Nombre_Empresa',
        'EMP_NIT_Empresa',
        'EMP_Telefono_Empresa',
        'EMP_Direccion_Empresa',
        'EMP_Correo_Empresa',
        'EMP_Logo_Empresa',
        'EMP_Empresa_Id',
        'EMP_Estado_Empresa'];
    protected $guarded = ['id'];

    public static function crearEmpresa($request){
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

    public static function editarEmpresa($request, $id){
        Empresas::findOrFail($id)->update([
            'EMP_Nombre_Empresa' => $request['EMP_Nombre_Empresa'],
            'EMP_NIT_Empresa' => $request['EMP_NIT_Empresa'],
            'EMP_Telefono_Empresa' => $request['EMP_Telefono_Empresa'],
            'EMP_Direccion_Empresa' => $request['EMP_Direccion_Empresa'],
            'EMP_Correo_Empresa' => $request['EMP_Correo_Empresa']
        ]);
    }

    public static function cambiarEstado($id){
        Empresas::findOrFail($id)->update([
            'EMP_Estado_Empresa' => 0
        ]);
    }
}
