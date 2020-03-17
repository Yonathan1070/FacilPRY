<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Decisiones;
use App\Http\Requests\ValidacionDecision;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Indicadores;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\DB;
use stdClass;

class DecisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-decisiones');
        $permisos = ['crear'=> can2('crear-decisiones'), 'editar'=>can2('editar-decisiones'), 'eliminar'=>can2('eliminar-decisiones')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $decisiones = DB::table('TBL_Indicadores as i')
            ->join('TBL_Decisiones as d', 'd.DSC_Indicador_Id', '=', 'i.id')
            ->get();
        #$decisiones = Decisiones::orderBy('id')->get();
        return view('decisiones.listar', compact('decisiones', 'datos', 'notificaciones', 'cantidad', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        can('crear-decisiones');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $indicadores = Indicadores::orderBy('id')->get();
        return view('decisiones.crear', compact('datos', 'indicadores', 'notificaciones', 'cantidad'));
    }

    public function totalIndicador($id)
    {
        $indicador = Indicadores::findOrFail($id);
        $diferencia = DB::table('TBL_Decisiones')
            ->select(DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia"))
            ->where('DSC_Indicador_Id', '=', $id)
            ->groupBy('id')
            ->get();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionDecision $request)
    {
        if ($request->DCS_Rango_Inicio_Decision > $request->DCS_Rango_Fin_Decision) {
            return redirect()->back()->withErrors('El Rango de inicio no puede ser mayor al de fin')->withInput();
        }
        $diferencia = DB::table('TBL_Decisiones')
            ->select(DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia"))
            ->where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)
            ->groupBy('id')
            ->get();
        $total = 0;
        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia;
        }
        if (((int) $request->DCS_Rango_Fin_Decision - (int) $request->DCS_Rango_Inicio_Decision) + $total > 100) {
            return redirect()->back()->withErrors('No se puede exceder del 100% del rango del indicador')->withInput();
        }
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)->select('DCS_Rango_Inicio_Decision', 'DCS_Rango_Fin_Decision', 'DCS_Nombre_Decision')->get();
        foreach ($decisiones as $decision) {
            if (
                $decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision < $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()->back()->withErrors('El Rango de inicio ya está siendo usado por otra decisión')->withInput();
            }
            if (
                $decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision < $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()->back()->withErrors('El Rango de fin ya está siendo usado por otra decisión')->withInput();
            }
            if ($decision->DCS_Nombre_Decision == $request->DCS_Nombre_Decision) {
                return redirect()->back()->withErrors('La desición ya está registrada en el sistema')->withInput();
            }
        }
        Decisiones::create($request->all());
        return redirect()->back()->with('mensaje', 'Decisión creada con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        can('editar-decisiones');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $indicadores = Indicadores::orderBy('id')->get();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $decision = Decisiones::findOrFail($id);
        return view('decisiones.editar', compact('decision', 'indicadores', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionDecision $request, $id)
    {
        if ($request->DCS_Rango_Inicio_Decision > $request->DCS_Rango_Fin_Decision) {
            return redirect()->back()->withErrors('El Rango de inicio no puede ser mayor al de fin')->withInput();
        }
        $diferencia = DB::table('TBL_Decisiones')
            ->select(DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia"))
            ->where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)
            ->where('id', '<>', $id)
            ->groupBy('id')
            ->get();
        $total = 0;
        foreach ($diferencia as $dif) {
            $total = $total + $dif->diferencia;
        }
        if (((int) $request->DCS_Rango_Fin_Decision - (int) $request->DCS_Rango_Inicio_Decision) + $total > 100) {
            return redirect()->back()->withErrors('No se puede exceder del 100% del rango del indicador')->withInput();
        }
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)
            ->where('id', '<>', $id)
            ->select('DCS_Rango_Inicio_Decision', 'DCS_Rango_Fin_Decision')->get();
        foreach ($decisiones as $decision) {
            if (
                $decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision < $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()->back()->withErrors('El Rango de inicio ya está siendo usado por otra decisión')->withInput();
            }
            if (
                $decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision < $decision->DCS_Rango_Fin_Decision
            ) {
                return redirect()->back()->withErrors('El Rango de fin ya está siendo usado por otra decisión')->withInput();
            }
        }
        Decisiones::findOrFail($id)->update($request->all());
        return redirect()->route('decisiones')->with('mensaje', 'Decisión actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
