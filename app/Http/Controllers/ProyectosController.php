<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Usuarios;
use App\Http\Requests\ValidacionProyecto;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\HorasActividad;
use App\Models\Tablas\Notificaciones;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use PDF;
use stdClass;

/**
 * Proyectos Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los proyectos
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class ProyectosController extends Controller
{
    /**
     * Muestra el listado de los proyectos de la empresa seleccionada
     *
     * @param  $id Identificador de la empresa
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index($id)
    {
        can('listar-proyectos');
        
        $permisos = [
            'crear'=> can2('crear-proyectos'),
            'listarR'=>can2('listar-requerimientos'),
            'listarA'=>can2('listar-actividades'),
            'listarE'=>can2('listar-empresas'),
            'eliminar'=>can2('eliminar-proyectos')
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

        $empresa = Empresas::findOrFail($id);
        $proyectosNoFinalizados = Proyectos::obtenerNoFinalizados($id);
        $proyectosFinalizados = Proyectos::obtenerFinalizados($id);

        return view(
            'proyectos.listar',
            compact(
                'proyectosNoFinalizados',
                'proyectosFinalizados',
                'empresa',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear proyectos
     *
     * @return \Illuminate\View\View Vista crear proyecto
     */
    public function crear($id)
    {
        can('crear-proyectos');
        
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
        $empresa = Empresas::findOrFail($id);
        $clientes = Usuarios::obtenerClientes($id);

        return view(
            'proyectos.crear',
            compact(
                'clientes',
                'empresa',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda el proyecto en la Base de Datos
     *
     * @param  App\Http\Requests\ValidacionProyecto $request
     * @return redirect()->back()->with()
     */
    public function guardar(ValidacionProyecto $request)
    {
        can('crear-proyectos');

        Proyectos::create($request->all());
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $datosU = Usuarios::findOrFail($request->PRY_Cliente_Id);
        
        if ($datos->USR_Supervisor_Id == 0)
            $datos->USR_Supervisor_Id = 1;
        
            Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.
                ' '.
                $datos->USR_Apellidos_Usuario.
                ' ha creado el proyecto '.
                $request->PRY_Nombre_Proyecto,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'proyectos',
            'id',
            $request->PRY_Empresa_Id,
            'library_add'
        );

        Notificaciones::crearNotificacion(
            'Hola! '.
                $datosU->USR_Nombres_Usuario.
                ' '.
                $datosU->USR_Apellidos_Usuario.
                ', su proyecto '.
                $request->PRY_Nombre_Proyecto.
                ' ha sido creado',
            session()->get('Usuario_Id'),
            $datosU->id,
            'proyectos',
            'id',
            $request->PRY_Empresa_Id,
            'library_add'
        );

        return redirect()
            ->route('crear_proyecto')
            ->with('mensaje', 'Proyecto agregado con éxito');
    }

    /**
     * Genera el PDF de las actividades del proyecto seleccionado
     *
     * @param  $id  Identificador del proyecto
     * @return PDF->download()
     */
    public function generarPdf($id)
    {
        can('listar-proyectos');

        $proyecto = Proyectos::findOrFail($id);
        $actividades = Actividades::obtenerActividadesPDF($id);

        if (count($actividades)<=0) {
            return redirect()
                ->route('proyectos')
                ->withErrors(
                    'No es posible generar el reporte de actividades debido '.
                        'a que no hay actividades registradas para el proyecto seleccionado!'
                );
        }

        $empresa = Empresas::findOrFail(session()->get('Empresa_Id'));
        
        $pdf = PDF::loadView(
            'includes.pdf.proyecto.actividades',
            compact('actividades', 'empresa')
        );

        $fileName = 'Actividades'.$proyecto->PRY_Nombre_Proyecto;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Obtiene el porcentaje de avance del proyecto
     *
     * @param  $id  Identificador del proyecto
     * @return response()->json()
     */
    public function obtenerPorcentaje($id)
    {
        can('listar-proyectos');

        $actividadesTotales = Actividades::obtenerActividadesTotales($id);
        $actividadesFinalizadas = Actividades::obtenerActividadesFinalizadas($id);
        
        if ((double)count($actividadesTotales) == 0) {
            $porcentaje = 0;
        } else {
            $division = (double)count($actividadesFinalizadas)/(double)count($actividadesTotales);
            $porcentaje = $division * 100;
        }

        $dato = new stdClass();
        $dato->porcentaje = (int)$porcentaje;

        return json_encode($dato);
    }

    /**
     * Obtiene los datos para generar el Diagrama de Gantt
     *
     * @param  $id  Identificador del proyecto
     */
    public function gantt($id)
    {
        can('listar-proyectos');

        $permisos = [
            'listarE'=>can2('listar-empresas')
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
        $proyecto = Proyectos::findOrFail($id);
        $fechas = HorasActividad::obtenerFechas($id);
        $actividades = HorasActividad::obtenerActividadesGantt($id);
        
        return view(
            'proyectos.gantt',
            compact(
                'actividades',
                'fechas',
                'proyecto',
                'permisos',
                'notificaciones',
                'cantidad',
                'datos',
                'asignadas'
            )
        );
    }

    /**
     * Obtiene los datos para descargar el Diagrama de Gantt en formato PDF
     *
     * @param  $id  Identificador del proyecto
     */
    public function ganttDescargar($id)
    {
        can('listar-proyectos');

        $proyecto = Proyectos::findOrFail($id);
        
        $fechas = HorasActividad::obtenerFechas($id);
        $actividades = HorasActividad::obtenerActividadesGantt($id);
        
        $pdf = PDF::loadView(
            'proyectos.ganttdos',
            compact(
                'actividades',
                'fechas',
                'proyecto')
            )->setPaper('a2', 'landscape');
        
        $fileName = 'Gantt'.$proyecto->PRY_Nombre_Proyecto;
        
        return $pdf->download($fileName.'.pdf');

    }

    /**
     * Finaliza el proyecto seleccionado
     *
     * @param  $id  Identificador del Proyecto
     * @return redirect()->back()->with()
     */
    public function finalizar($id)
    {
        can('editar-proyectos');

        Proyectos::finalizarProyecto($id);
        
        return redirect()
            ->route('proyectos')
            ->with('mensaje', 'Proyecto finalizado');
    }

    /**
     * Reactivar el proyecto seleccionado
     *
     * @param  $id  Identificador del Proyecto
     * @return redirect()->back()->with()
     */
    public function activar($id)
    {
        can('editar-proyectos');
        
        Proyectos::activarProyecto($id);
        
        return redirect()
            ->route('proyectos')
            ->with('mensaje', 'Proyecto activado');
    }

    /**
     * Eliminar el proyecto seleccionado
     *
     * @param  $idP  Identificador del Proyecto
     * @return redirect()->back()->with()
     */
    public function eliminar(Request $request, $idP)
    {
        if (!can('eliminar-proyectos')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    $actividades = Actividades::obtenerActividadesProyecto($idP);
                    if (count($actividades) == 0) {
                        Proyectos::destroy($idP);
                        return response()->json(['mensaje' => 'ok']);
                    } else {
                        return response()->json(['mensaje' => 'ng']);
                    }
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
        can('editar-proyectos');

        Proyectos::finalizarProyecto($id);
        
        return redirect()
            ->route('proyectos')
            ->with('mensaje', 'Proyecto finalizado');
    }
}