<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Decisiones;
use App\Http\Requests\ValidacionDecision;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Indicadores;
use App\Models\Tablas\Notificaciones;
use Illuminate\Database\QueryException;
use stdClass;

class DecisionesController extends Controller
{
    /**
     * Muestra el listado de las decisiones registradas en el sistema
     *
     * @return \Illuminate\View\View Vista del listado de decisiones
     */
    public function index()
    {
        can('listar-decisiones');

        $permisos = [
            'crear'=> can2('crear-decisiones'),
            'editar'=>can2('editar-decisiones'),
            'eliminar'=>can2('eliminar-decisiones'),
            'listar'=>can2('listar-calificaciones')
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
        $decisiones = Decisiones::obtenerDecisiones();
        
        return view(
            'decisiones.listar',
            compact(
                'decisiones',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear la decision
     *
     * @return \Illuminate\View\View Vista del formulario para crear decisiones
     */
    public function crear()
    {
        can('crear-decisiones');
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
        $indicadores = Indicadores::orderBy('id')->get();

        return view(
            'decisiones.crear',
            compact(
                'datos',
                'indicadores',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Obtiene el total del indicador en un json
     *
     * @return json_encode()
     */
    public function totalIndicador($id)
    {
        $indicador = Indicadores::findOrFail($id);
        $diferencia = Decisiones::obtenerDiferenciaDecisiones($id);
        $total = 0;

        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia;
        }

        $dato = new stdClass();
        $dato->total = $total;
        $dato->indicador = $indicador->INDC_Nombre_Indicador;

        return json_encode($dato);
    }

    /**
     * Guarda los datos de la decision en la Base de datos
     *
     * @param  App\Http\Requests\ValidacionDecision $request
     * @return redirect()->back()->withErrors()->withInput();
     */
    public function guardar(ValidacionDecision $request)
    {
        can('crear-decisiones');

        if (
            $request->DCS_Rango_Inicio_Decision > $request->DCS_Rango_Fin_Decision
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'El Rango de inicio no puede ser mayor al de fin'
                )->withInput();
        }

        $decisiones = Decisiones::get();

        $diferencia = Decisiones::obtenerDiferenciaDecisiones(2);
        
        $total = 0;
        foreach ($decisiones as $decision) {
            if ($decision->DCS_Rango_Inicio_Decision == 0) {
                $total = -1;
                break;
            }

        }

        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia;
        }

        if (
            (
                (int) $request->DCS_Rango_Fin_Decision - (int) $request->DCS_Rango_Inicio_Decision
            ) + $total > 100
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'No se puede exceder del 100% del rango de la decisión'
                )->withInput();
        }
        
        $decisiones = Decisiones::obtenerDecisionById(2);
        
        foreach ($decisiones as $decision) {
            if (
                $decision->DCS_Rango_Inicio_Decision <= $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision <= $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El Rango de inicio ya está siendo usado por otra decisión'
                    )->withInput();
            }

            if (
                $decision->DCS_Rango_Inicio_Decision <= $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision <= $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El Rango de fin ya está siendo usado por otra decisión'
                    )->withInput();
            }

            if ($decision->DCS_Nombre_Decision == $request->DCS_Nombre_Decision) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'La decisión ya está registrada en el sistema'
                    )->withInput();
            }
        }

        Decisiones::crearDecision($request, 2);

        return redirect()
            ->back()
            ->with('mensaje', 'Decisión creada con éxito');
    }

    /**
     * Muestra el formulario para editar la decision
     *
     * @return \Illuminate\View\View Vista del formulario para editar decisiones
     */
    public function editar($id)
    {
        can('editar-decisiones');

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

        $indicadores = Indicadores::orderBy('id')->get();
        $decision = Decisiones::findOrFail($id);

        return view(
            'decisiones.editar',
            compact(
                'decision',
                'indicadores',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Actualiza los datos de la decision en la Base de datos
     *
     * @param  App\Http\Requests\ValidacionDecision $request
     * @return redirect()->back()->withErrors()->withInput();
     */
    public function actualizar(ValidacionDecision $request, $id)
    {
        can('editar-decisiones');
        if (
            $request->DCS_Rango_Inicio_Decision > $request->DCS_Rango_Fin_Decision
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'El Rango de inicio no puede ser mayor al de fin'
                )->withInput();
        }

        $diferencia = Decisiones::obtenerDiferenciaDecisionDistintasId($id, 2);
        $total = 0;
        foreach ($diferencia as $decision) {
            if ($decision->DCS_Rango_Inicio_Decision == 0) {
                $total = -1;
                break;
            }

        }

        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia + 1;
        }
        
        if (
            (
                (int) $request->DCS_Rango_Fin_Decision - (int) $request->DCS_Rango_Inicio_Decision
            ) + $total > 100
        ) {
            return redirect()
                ->back()
                ->withErrors(
                    'No se puede exceder del 100% del rango del indicador'
                )->withInput();
        }

        $decisiones = Decisiones::obtenerDecisionDistintasId($id, 2);
        
        foreach ($decisiones as $decision) {
            if (
                $decision->DCS_Rango_Inicio_Decision <= $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision <= $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El Rango de inicio ya está siendo usado por otra decisión'
                    )->withInput();
            }
            if (
                $decision->DCS_Rango_Inicio_Decision <= $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision <= $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()
                    ->back()
                    ->withErrors(
                        'El Rango de fin ya está siendo usado por otra decisión'
                    )->withInput();
            }
        }

        Decisiones::findOrFail($id)->update($request->all());

        return redirect()
            ->route('decisiones')
            ->with('mensaje', 'Decisión actualizada con éxito');
    }

    /**
     * Elimina los datos de la decision en la Base de datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return response()->json();
     */
    public function eliminar(Request $request, $id)
    {
        if (!can('eliminar-decisiones')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    Decisiones::destroy($id);
                    return response()->json(['mensaje' => 'ok']);
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }
}