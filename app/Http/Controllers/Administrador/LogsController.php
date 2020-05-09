<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\Tablas\LogCambios;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Usuarios;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    /**
     * Muestra el listado de los logs de cambios de los ultimos 8 días
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # Datos de las notificaciones y del usuario
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $logs = LogCambios::obtenerLogsOchoDias();

        return view(
            'administrador.logs.listado',
            compact(
                'datos',
                'notificaciones', 
                'cantidad',
                'logs'
            )
        );
    }

    /**
     * Muestra el listado de los logs de cambios de los ultimos 8 días
     *
     * @return \Illuminate\Http\Response
     */
    public function historico()
    {
        # Datos de las notificaciones y del usuario
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $logs = LogCambios::obtenerLogs();

        return view(
            'administrador.logs.todos',
            compact(
                'datos',
                'notificaciones', 
                'cantidad',
                'logs'
            )
        );
    }
}
