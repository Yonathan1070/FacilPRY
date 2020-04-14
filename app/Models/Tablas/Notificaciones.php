<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Notificaciones, modelo que contiene los distintos
 * atributos de la tabla notificaciones en la Base de Datos
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
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

    #Funcion donde obtenemos el listado de las notificaciones de cada usuario
    public static function obtenerNotificaciones($id)
    {
        $notificaciones = Notificaciones::where(
            'NTF_Para', '=', $id
        )->orderByDesc('created_at')
            ->get();
        
        return $notificaciones;
    }

    #Función donde obtenemos la cantidad de notificaciones sin abrir de cada usuario
    public static function obtenerCantidadNotificaciones($id)
    {
        $cantidad = Notificaciones::where(
            'NTF_Para', '=', $id
        )->where(
            'NTF_Estado', '=', 0
        )->count();
        
        return $cantidad;
    }

    #Funcion donde se guardan las notificaciones en la Base de Datos
    public static function crearNotificacion(
        $titulo, $de, $para, $ruta, $parametro, $valor, $icono
    )
    {
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

    #Funcion que vambia el estado de la notificación a visto
    public static function cambiarEstadoNotificacion($id)
    {
        $notificacion = Notificaciones::findOrFail($id);
        $notificacion->update([
            'NTF_Estado' => 1
        ]);
        return $notificacion;
    }

    #Funcion que cambia el estado de todas las notificaciones a vistas
    public static function cambiarEstadoTodas($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', $id)->get();
        foreach ($notificaciones as $notificacion) {
            $notificacion->update([
                'NTF_Estado' => 1
            ]);
        }
    }
}
