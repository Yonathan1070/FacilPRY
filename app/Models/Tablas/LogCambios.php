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
}
