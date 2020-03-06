<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionRequerimiento;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Requerimientos Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los requerimientos de cada proyecto
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class RequerimientosController extends Controller
{
    /**
     * Muestra el listado de los requerimientos para el proyecto
     * seleccionado.
     *
     * @param  $idP Identificador del proyecto
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index($idP)
    {
        can('listar-requerimientos');
        $permisos = [
            'crear'=> can2('crear-requerimientos'),
            'editar'=>can2('editar-requerimientos'),
            'eliminar'=>can2('eliminar-requerimientos'),
            'listarA'=>can2('listar-actividades')
        ];
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $requerimientos = Requerimientos::obtenerRequerimientos($idP);
        $proyecto = Proyectos::findOrFail($idP);
        
        return view(
            'requerimientos.listar',
            compact(
                'requerimientos',
                'proyecto',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos'
            )
        );
    }

    /**
     * Muestra el formulario para crear requerimientos
     *
     * @param  $idP Identificador del proyecto
     * @return \Illuminate\View\View Vista para crear requerimientos
     */
    public function crear($idP)
    {
        can('crear-requerimientos');
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        
        return view(
            'requerimientos.crear',
            compact(
                'proyecto',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Guarda los datos del requerimiento en la base de datos
     *
     * @param  App\Http\Requests\ValidacionRequerimiento  $request
     * @return redirect()->route()->with()
     */
    public function guardar(ValidacionRequerimiento $request)
    {
        $datosU = Usuarios::obtenerClienteProyecto($request->REQ_Proyecto_Id);
        $requerimientos = Requerimientos::obtenerRequerimientos($request['REQ_Proyecto_Id']);
        
        foreach ($requerimientos as $requerimiento) {
            if (
                $requerimiento->REQ_Nombre_Requerimiento == $request['REQ_Nombre_Requerimiento']
            ) {
                return redirect()
                    ->back()
                    ->withErrors('El requerimiento ya se encuentra registrado para este proyecto.')
                    ->withInput();
            }
        }
        
        Requerimientos::create($request->all());
        
        Notificaciones::crearNotificacion(
            'Se han agregado actividades al proyecto '.$datosU->PRY_Nombre_Proyecto,
            session()->get('Usuario_Id'),
            $datosU->id,
            'requerimientos',
            'idP',
            $request->REQ_Proyecto_Id,
            'library_add'
        );

        return redirect()
            ->route(
                'crear_requerimiento', [$request['REQ_Proyecto_Id']]
            )->with('mensaje', 'Requerimiento agregado con exito');
    }

    /**
     * Muestra el formulario para editar el requerimiento
     *
     * @param  $idP  Identificador del proyecto
     * @param  $idR  Identificador del requerimiento
     * @return \Illuminate\View\View Vista para editar requerimientos
     */
    public function editar($idP, $idR)
    {
        can('editar-requerimientos');
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        $requerimiento = Requerimientos::findOrFail($idR);
        
        return view(
            'requerimientos.editar',
            compact(
                'proyecto',
                'requerimiento',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Actualiza los datos del requerimiento
     *
     * @param  App\Http\Requests\ValidacionRequerimiento  $request
     * @param  $idR Identificador del requerimiento
     * @return redirect()->route()->with()
     */
    public function actualizar(ValidacionRequerimiento $request, $idR)
    {
        $datosU = Usuarios::obtenerClienteProyecto($request->REQ_Proyecto_Id);
        $requerimientos = Requerimientos::obtenerRequerimientosNoActual($request, $idR);
        
        foreach ($requerimientos as $requerimiento) {
            if (
                $requerimiento->REQ_Nombre_Requerimiento == $request['REQ_Nombre_Requerimiento']
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El requerimiento ya se encuentra registrado para este proyecto.'
                    )->withInput();
            }
        }
        return redirect()
            ->route(
                'requerimientos', [$request['REQ_Proyecto_Id']]
            )->with('mensaje', 'Requerimiento actualizado con exito');
    }

    /**
     * Elimina el requerimiento de la base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $idP Identificador del proyecto
     * @param  $idR Identificador del requerimiento
     * @return response()->json()
     */
    public function eliminar(Request $request, $idP, $idR)
    {
        if (!can('eliminar-requerimientos')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    $datosU = Usuarios::obtenerClienteProyecto($idP);
                    Requerimientos::destroy($idR);
                    
                    Notificaciones::crearNotificacion(
                        'Se ha eliminado una actividad de su proyecto.',
                        session()->get('Usuario_Id'),
                        $datosU->id,
                        'inicio_cliente',
                        null,
                        null,
                        'delete_forever'
                    );
                    
                    return response()->json(['mensaje' => 'ok']);
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }

    /**
     * Obtiene el porcentaje de avance del requerimiento
     *
     * @param  $id  Identificador del requerimiento
     * @return response()->json()
     */
    public function obtenerPorcentaje($id)
    {
        $actividadesTotales = Actividades::obtenerActividadesTotalesRequerimiento($id);
        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadasRequerimiento($id);
        
        if((double)count($actividadesTotales) == 0){
            $porcentaje = 0;
        }else {
            $division = (double)count($actividadesFinalizadas)/(double)count($actividadesTotales);
            $porcentaje = $division * 100;
        }
        
        $dato = new stdClass();
        $dato->porcentaje = (int)$porcentaje;
        
        return json_encode($dato);
    }
}