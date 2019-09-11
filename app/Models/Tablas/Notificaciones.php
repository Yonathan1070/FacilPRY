<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notificaciones extends Model
{
    protected $table = "TBL_Notificaciones";
    protected $fillable = ['NTF_Titulo',
        'NTF_De',
        'NTF_Para',
        'NTF_Fecha',
        'NTF_Route',
        'NTF_Parametro',
        'NTF_Valor_Parametro',
        'NTF_Estado',
        'NTF_Icono'];
    protected $guarded = ['id'];

    public static function crearNotificacion($titulo, $de, $para, $ruta, $parametro, $valor, $icono){
        Notificaciones::create([
            'NTF_Titulo' => $titulo,
            'NTF_De' => $de,
            'NTF_Para' => $para,
            'NTF_Fecha' => Carbon::now(),
            'NTF_Route' => $ruta,
            'NTF_Parametro' => $parametro,
            'NTF_Valor_Parametro' => $valor,
            'NTF_Estado' => 0,
            'NTF_Icono' => $icono
        ]);
    }
}