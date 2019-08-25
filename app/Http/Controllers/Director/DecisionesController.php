<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Decisiones;
use App\Http\Requests\ValidacionDecision;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Indicadores;
use App\Models\Tablas\Notificaciones;
use stdClass;
use Illuminate\Support\Facades\DB;

class DecisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $decisiones = DB::table('TBL_Indicadores as i')
            ->join('TBL_Decisiones as d', 'd.DSC_Indicador_Id', '=', 'i.id')
            ->get();
        return view('director.decisiones.listar', compact('decisiones', 'datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $indicadores = Indicadores::orderBy('id')->get();
        return view('director.decisiones.crear', compact('datos', 'indicadores'));
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
        $total=0;
        foreach ($diferencia as $dif) {
            $total = $total+$dif->diferencia;
        }
        if(((int)$request->DCS_Rango_Fin_Decision-(int)$request->DCS_Rango_Inicio_Decision)+$total > 100){
            return redirect()->back()->withErrors('No se puede exceder del 100% del rango del indicador')->withInput();
        }
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)->select('DCS_Rango_Inicio_Decision', 'DCS_Rango_Fin_Decision', 'DCS_Nombre_Decision')->get();
        foreach ($decisiones as $decision) {
            if($decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision < $decision->DCS_Rango_Fin_Decision){
                return redirect()->back()->withErrors('El Rango de inicio ya está siendo usado por otra decisión')->withInput();
            }
            if($decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision < $decision->DCS_Rango_Fin_Decision){
                return redirect()->back()->withErrors('El Rango de fin ya está siendo usado por otra decisión')->withInput();
            }
            if($decision->DSC_Nombre_Decision == $request->DSC_Nombre_Decision){
                return redirect()->back()->withErrors('La desición ya está registrada en el sistema')->withInput();
            }
        }
        Decisiones::create($request->all());
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha creado la desición '.$request->DCS_Nombre_Decision,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'administrador/decisiones',
            'add_circle'
        );
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
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $decision = Decisiones::findOrFail($id);
        $indicadores = Indicadores::orderBy('id')->get();
        return view('director.decisiones.editar', compact('decision', 'datos', 'indicadores'));
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
        $total=0;
        foreach ($diferencia as $dif) {
            $total = $total+$dif->diferencia;
        }
        if(((int)$request->DCS_Rango_Fin_Decision-(int)$request->DCS_Rango_Inicio_Decision)+$total > 100){
            return redirect()->back()->withErrors('No se puede exceder del 100% del rango del indicador')->withInput();
        }
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $request->DSC_Indicador_Id)
            ->where('id', '<>', $id)
            ->select('DCS_Rango_Inicio_Decision', 'DCS_Rango_Fin_Decision', 'DCS_Nombre_Decision')->get();
        foreach ($decisiones as $decision) {
            if($decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Inicio_Decision &&
                $request->DCS_Rango_Inicio_Decision < $decision->DCS_Rango_Fin_Decision){
                return redirect()->back()->withErrors('El Rango de inicio ya está siendo usado por otra decisión')->withInput();
            }
            if($decision->DCS_Rango_Inicio_Decision < $request->DCS_Rango_Fin_Decision &&
                $request->DCS_Rango_Fin_Decision < $decision->DCS_Rango_Fin_Decision){
                return redirect()->back()->withErrors('El Rango de fin ya está siendo usado por otra decisión')->withInput();
            }
            if($decision->DSC_Nombre_Decision == $request->DSC_Nombre_Decision){
                return redirect()->back()->withErrors('La desición ya está registrada en el sistema')->withInput();
            }
        }
        Decisiones::findOrFail($id)->update($request->all());
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha actualizado la desición '.$request->DCS_Nombre_Decision,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'administrador/decisiones',
            'update'
        );
        return redirect()->back()->with('mensaje', 'Decisión actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $id)
    {
        try{
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $desicion = Decisiones::findOrFail($id);
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.' '.$datos->USR_Apellidos_Usuario.' ha eliminado la decision '.$desicion->DCS_Nombre_Decision,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'administrador/decisiones',
                'delete_forever'
            );
            Decisiones::destroy($id);
            return redirect()->back()->with('mensaje', 'La Decisión fue eliminada satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->back()->withErrors(['La Decision está siendo usada por otro recurso.']);
        }
    }

    public function totalIndicador($id){
        $indicador = Indicadores::findOrFail($id);
        $diferencia = DB::table('TBL_Decisiones')
            ->select(DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia"))
            ->where('DSC_Indicador_Id', '=', $id)
            ->groupBy('id')
            ->get();
        $total=0;
        foreach ($diferencia as $dif) {
            $total = $total+$dif->diferencia;
        }
        $dato = new stdClass();
        $dato->total = $total;
        $dato->indicador = $indicador->INDC_Nombre_Indicador;
        return json_encode($dato);
    }
}
