<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Calificaciones, realiza las distintas consultas
 * que tenga que ver con la tabla Calificaciones en la
 * Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Calificaciones extends Model
{
    protected $table = "TBL_Calificaciones";
    protected $fillable = ['CALIF_calificacion',
        'CALIF_Trabajador_Id',
        'CALIF_Decision_Id',
        'CALIF_Fecha_Calificacion'];
    protected $guarded = ['id'];

    #Función para obtener las calificaciones
    public static function obtenerCalificaciones()
    {
        $calificaciones = DB::table('TBL_Calificaciones as c')
            ->join(
                'TBL_Decisiones as d',
                'd.id',
                '=',
                'c.CALIF_Decision_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'c.CALIF_Trabajador_Id'
            )->select(
                'c.*',
                'd.*',
                'u.*',
                'c.id as Id_Calificacion'
            )->get();
        
        return $calificaciones;
    }
    
    #Función para obtener las calificaciones por rango de fechas
    public static function obtenerCalificacionesFecha($fechaActual)
    {
        $calificaciones = DB::table('TBL_Calificaciones as c')
            ->join(
                'TBL_Decisiones as d',
                'd.id',
                '=',
                'c.CALIF_Decision_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'c.CALIF_Trabajador_Id'
            )->select(
                'c.*',
                'd.*',
                'u.*',
                'c.id as Id_Calificacion'
            )->where(
                'c.CALIF_Fecha_Calificacion', '=', $fechaActual
            )->get();
        
        return $calificaciones;
    }

    #Función para obtener las calificaciones por id
    public static function obtenerCalificacionId($id)
    {
        $calificaciones = DB::table('TBL_Calificaciones as c')
            ->join(
                'TBL_Decisiones as d',
                'd.id',
                '=',
                'c.CALIF_Decision_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'c.CALIF_Trabajador_Id'
            )->select(
                'c.*',
                'd.*',
                'u.*'
            )->where(
                'c.id', '=', $id
            )->first();
        
        return $calificaciones;
    }

    #Función para guardar la calificación obtenida
    public static function guardarCalificacion($calificacion, $trabajador, $decision)
    {
        Calificaciones::create([
            'CALIF_calificacion' => $calificacion,
            'CALIF_Trabajador_Id' => $trabajador,
            'CALIF_Decision_Id' => $decision,
            'CALIF_Fecha_Calificacion' => Carbon::now()
        ]);
    }
}
