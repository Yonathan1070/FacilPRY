<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Respuesta, realiza las distintas consultas que tenga que 
 * ver con la tabla Respuestas en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Respuesta extends Model
{
    protected $table = "TBL_Respuesta";
    protected $fillable = ['RTA_Titulo',
        'RTA_Respuesta',
        'RTA_Actividad_Finalizada_Id',
        'RTA_Estado_Id',
        'RTA_Usuario_Id',
        'RTA_Fecha_Respuesta'];
    protected $guarded = ['id'];

    //Funcion para obtener el historial de respuestas
    public static function obtenerHistoricoRespuestas($id)
    {
        $respuestasAnteriores = DB::table('TBL_Respuesta as r')
            ->join(
                'TBL_Actividades_Finalizadas as af',
                'af.id',
                '=',
                'r.RTA_Actividad_Finalizada_Id'
            )
            ->join('TBL_Usuarios as u', 'u.id', '=', 'r.RTA_Usuario_Id')
            ->select('r.*', 'u.*', 'af.*')
            ->where('af.ACT_FIN_Actividad_Id', '=', $id)
            ->where('r.RTA_Titulo', '<>', null)
            ->get();

        return $respuestasAnteriores;
    }
}
