<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LogCambios extends Model
{
    protected $table = "TBL_Log_Cambios";
    protected $fillable = [
        'LOG_Tabla', 
        'LOG_Accion',
        'LOG_Descripcion',
        'LOG_Fecha',
        'LOG_Usuario'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    #Función para guardar la información en la Base de Datos
    public static function guardar($tabla, $accion, $descripcion, $usuario)
    {
        LogCambios::create([
            'LOG_Tabla' => $tabla,
            'LOG_Accion' => $accion,
            'LOG_Descripcion' => $descripcion,
            'LOG_Fecha' => Carbon::now(),
            'LOG_Usuario' => $usuario
        ]);
    }

    #Función para obtener el listado de logs
    public static function obtenerLogsOchoDias()
    {
        $logs = LogCambios::join(
            'TBL_Usuarios as u',
            'u.id',
            '=',
            'TBL_Log_Cambios.LOG_Usuario'
        )->where('LOG_Fecha', '>',Carbon::now()->subDays(8))
        ->get();

        return $logs;
    }

    #Función para obtener el listado de logs
    public static function obtenerLogs()
    {
        $logs = LogCambios::join(
            'TBL_Usuarios as u',
            'u.id',
            '=',
            'TBL_Log_Cambios.LOG_Usuario'
        )->get();

        return $logs;
    }
}
