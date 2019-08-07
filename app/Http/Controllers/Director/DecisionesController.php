<?php

namespace App\Http\Controllers\Director;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Decisiones;
use App\Http\Requests\ValidacionDecision;
use App\Models\Tablas\Usuarios;

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
        $decisiones = Decisiones::orderBy('id')->get();
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
        return view('director.decisiones.crear', compact('datos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionDecision $request)
    {
        if ($request['DCS_Rango_Inicio_Decision'] >= $request['DCS_Rango_Fin_Decision']) {
            return redirect()->route('crear_decision_director')->withErrors('Digite un rango válido')->withInput();
        }
        Decisiones::create($request->all());
        return redirect()->route('crear_decision_director')->with('mensaje', 'Decisión creada con exito');
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
        return view('director.decisiones.editar', compact('decision', 'datos'));
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
        if ($request['DCS_Rango_Inicio_Decision'] >= $request['DCS_Rango_Fin_Decision']) {
            return redirect()->route('editar_decision_director')->withErrors('Digite un rango válido')->withInput();
        }
        Decisiones::findOrFail($id)->update($request->all());
        return redirect()->route('decisiones_director')->with('mensaje', 'Decisión actualizada con exito');
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
            Decisiones::destroy($id);
            return redirect()->route('decisiones_director')->with('mensaje', 'La Decisión fue eliminada satisfactoriamente.');
        }catch(QueryException $e){
            return redirect()->route('decisiones_director')->withErrors(['La Decision está siendo usada por otro recurso.']);
        }
    }
}
