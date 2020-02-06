<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Proyectos extends Model
{
    protected $table = "TBL_Proyectos";
    protected $fillable = ['PRY_Nombre_Proyecto',
        'PRY_Descripcion_Proyecto',
        'PRY_Cliente_Id',
        'PRY_Empresa_Id',
        'PRY_Estado_Proyecto'];
    protected $guarded = ['id'];

    public static function cambiarEstado($id){
        Proyectos::where('PRY_Empresa_Id', '=', $id)->update([
            'PRY_Estado_Proyecto' => 0
        ]);
    }

    public static function cambiarEstadoActivado($id){
        Proyectos::where('PRY_Empresa_Id', '=', $id)->update([
            'PRY_Estado_Proyecto' => 1
        ]);
    }
}
