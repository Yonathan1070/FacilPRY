<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Horas Actividad, donde se establecen los atributos de la tabla en la 
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
class HorasActividad extends Model
{
    protected $table = "TBL_Horas_Actividad";
    protected $fillable = ['HRS_ACT_Actividad_Id',
        'HRS_ACT_Fecha_Actividad',
        'HRS_ACT_Cantidad_Horas_Asignadas',
        'HRS_ACT_Cantidad_Horas_Reales'];
    protected $guarded = ['id'];

    //Función que obtiene las fechas para el diagrama de Gantt
    public static function obtenerFechas($id)
    {
        $fechas = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*', 'ha.*')
            ->where('p.id', '=', $id)
            ->orderBy('ha.HRS_ACT_Fecha_Actividad')
            ->groupBy('ha.HRS_ACT_Fecha_Actividad')
            ->get();
        
        return $fechas;
    }

    //Función que obtiene las actividades para el diagrama de Gantt
    public static function obtenerActividadesGantt($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->leftjoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->select('a.id as Actividad_Id', 'a.*')
            ->where('p.id', '=', $id)
            ->orderby('a.ACT_Fecha_Inicio_Actividad')
            ->get();
        
        return $actividades;
    }

    //Función para crear las Horas asignadas para las actividades
    public static function crearHorasActividad($actividad, $fecha){
        HorasActividad::create([
            'HRS_ACT_Actividad_Id' => $actividad->id,
            'HRS_ACT_Fecha_Actividad' => $fecha . " 23:59:00"
        ]);
    }
}
