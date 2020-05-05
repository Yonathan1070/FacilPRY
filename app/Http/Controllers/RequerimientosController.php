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

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
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
                'permisos',
                'asignadas'
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
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $proyecto = Proyectos::findOrFail($idP);
        
        return view(
            'requerimientos.crear',
            compact(
                'proyecto',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
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
        can('crear-requerimientos');

        $datosU = Usuarios::obtenerClienteProyecto($request->REQ_Proyecto_Id);
        $requerimientos = Requerimientos::obtenerRequerimientos($request['REQ_Proyecto_Id']);
        
        foreach ($requerimientos as $requerimiento) {
            if (
                strtolower($requerimiento->REQ_Nombre_Requerimiento) == strtolower($request['REQ_Nombre_Requerimiento'])
            ) {
                return redirect()
                    ->back()
                    ->withErrors('El proyecto ya cuenta con una actividad del mismo nombre.')
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
            )->with('mensaje', 'Actividad agregada con éxito');
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
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $proyecto = Proyectos::findOrFail($idP);
        $requerimiento = Requerimientos::findOrFail($idR);
        
        return view(
            'requerimientos.editar',
            compact(
                'proyecto',
                'requerimiento',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
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
        can('editar-requerimientos');

        $requerimientos = Requerimientos::obtenerRequerimientosNoActual($request, $idR);
        
        foreach ($requerimientos as $requerimiento) {
            if (
                $requerimiento->REQ_Nombre_Requerimiento == $request['REQ_Nombre_Requerimiento']
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El proyecto ya cuenta con una actividad del mismo nombre.'
                    )->withInput();
            }
        }
        
        Requerimientos::actualizar($request, $idR);
        
        return redirect()
            ->route(
                'requerimientos', [$request['REQ_Proyecto_Id']]
            )->with('mensaje', 'Actividad actualizado con éxito');
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
        can('listar-requerimientos');

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