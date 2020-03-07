<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
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

    //Funcion para obtener la respuesta del validador
    public static function obtenerRespuestaValidador($id)
    {
        $rtaValidador = Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $id)
            ->where('RTA_Usuario_Id', '<>', 0)
            ->first();

        return $rtaValidador;
    }

    //Funcion para crear el historial de respuestas
    public static function crearRespuesta($id, $estado)
    {
        Respuesta::create([
            'RTA_Actividad_Finalizada_Id' => $id,
            'RTA_Estado_Id' => $estado
        ]);
    }

    //Funcion para actualizar los datos de la respuesta
    public static function actualizarRespuesta($request, $estado)
    {
        Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Titulo', '=', null)
            ->first()
            ->update([
                'RTA_Titulo'=>$request->RTA_Titulo,
                'RTA_Respuesta' => $request->RTA_Respuesta,
                'RTA_Estado_Id' => $estado,
                'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                'RTA_Fecha_Respuesta' => Carbon::now()
            ]);
    }

    //Funcion para actualizar la respuesta cliente
    public static function actualizarRespuestaCliente($request, $estado)
    {
        Respuesta::where('RTA_Actividad_Finalizada_Id', '=', $request->id)
            ->where('RTA_Usuario_Id', '=', 0)
            ->first()
            ->update([
                'RTA_Titulo' => $request->RTA_Titulo,
                'RTA_Respuesta' => $request->RTA_Respuesta,
                'RTA_Estado_Id' => $estado,
                'RTA_Usuario_Id' => session()->get('Usuario_Id'),
                'RTA_Fecha_Respuesta' => Carbon::now()
            ]);
    }
}
