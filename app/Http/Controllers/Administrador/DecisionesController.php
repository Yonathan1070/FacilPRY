<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Decisiones;
use App\Http\Requests\ValidacionDecision;

class DecisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $decisiones = Decisiones::orderBy('id')->get();
        return view('administrador.decisiones.listar', compact('decisiones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        return view('administrador.decisiones.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionDecision $request)
    {
        Decisiones::create($request->all());
        return redirect('administrador/decisiones')->with('mensaje', 'Decisión creada con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $decision = Decisiones::findOrFail($id);
        return view('administrador.decisiones.editar', compact('decision'));
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
        Decisiones::findOrFail($id)->update($request->all());
        return redirect('administrador/decisiones')->with('mensaje', 'Decisión actualizada con exito');
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
            return redirect('administrador/decisiones')->with('mensaje', 'La Decisión fue eliminada satisfactoriamente.');
        }catch(QueryException $e){
            return redirect('administrador/decisiones')->withErrors(['La Decision está siendo usada por otro recurso.']);
        }
    }
}
