<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Proyectos, donde se establecen los atributos de la tabla en la 
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
class Proyectos extends Model
{
    protected $table = "TBL_Proyectos";
    protected $fillable = ['PRY_Nombre_Proyecto',
        'PRY_Descripcion_Proyecto',
        'PRY_Cliente_Id',
        'PRY_Empresa_Id',
        'PRY_Estado_Proyecto',
        'PRY_Finalizado_Proyecto'];
    protected $guarded = ['id'];

    //Función que cambia el estado del proyecto
    public static function cambiarEstado($id)
    {
        Proyectos::where('PRY_Empresa_Id', '=', $id)
            ->update([
                'PRY_Estado_Proyecto' => 0
            ]);
    }

    //Función que cambia el estado a activo el proyecto
    public static function cambiarEstadoActivado($id)
    {
        Proyectos::where('PRY_Empresa_Id', '=', $id)
            ->update([
                'PRY_Estado_Proyecto' => 1
            ]);
    }

    //Funcion que cambia el estado del proyecto a finalizado
    public static function finalizarProyecto($id)
    {
        Proyectos::where('id', '=', $id)
            ->update([
                'PRY_Finalizado_Proyecto' => 1
            ]);
    }

    //Funcion que reactiva el proyecto
    public static function activarProyecto($id)
    {
        Proyectos::where('id', '=', $id)
            ->update([
                'PRY_Finalizado_Proyecto' => 0
            ]);
    }
}
