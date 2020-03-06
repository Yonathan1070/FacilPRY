<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    //Función para obtener los proyectos no finalizados
    public static function obtenerNoFinalizados($id)
    {
        $proyectosNoFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin('TBL_Requerimientos as r', 'r.REQ_Proyecto_Id', '=', 'p.id')
            ->leftjoin('TBL_Actividades as a', 'a.ACT_Requerimiento_Id', '=', 'r.id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('u.*', 'p.*', 'p.id as Proyecto_Id', DB::raw('COUNT(a.id) as Actividades_Totales'), DB::raw('COUNT(af.id) as Actividades_Finalizadas'))
            ->where('p.PRY_Empresa_Id', '=', $id)
            ->where('p.PRY_Finalizado_Proyecto', '=', 0)
            ->groupBy('p.id')
            ->get();
        
        return $proyectosNoFinalizados;
    }

    //Función para obtener los proyectos finalizados
    public static function obtenerFinalizados($id)
    {
        $proyectosFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin('TBL_Requerimientos as r', 'r.REQ_Proyecto_Id', '=', 'p.id')
            ->leftjoin('TBL_Actividades as a', 'a.ACT_Requerimiento_Id', '=', 'r.id')
            ->leftjoin('TBL_Actividades_Finalizadas as af', 'af.ACT_FIN_Actividad_Id', '=', 'a.id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('u.*', 'p.*', 'p.id as Proyecto_Id', DB::raw('COUNT(a.id) as Actividades_Totales'), DB::raw('COUNT(af.id) as Actividades_Finalizadas'))
            ->where('p.PRY_Empresa_Id', '=', $id)
            ->where('p.PRY_Finalizado_Proyecto', '=', 1)
            ->groupBy('p.id')
            ->get();
        
        return $proyectosFinalizados;
    }

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
