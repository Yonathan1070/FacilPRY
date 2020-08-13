<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Parrilla;
use App\Models\Tablas\Publicacion;
use App\Models\Tablas\Piezas;
use Illuminate\Support\Facades\DB;

class PiezasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('PZA_Archivo')) {
            foreach ($request->file('PZA_Archivo') as $documento) {
                $archivo = null;
                if ($documento->isValid()) {
                    $archivo = time() . '.' . $documento->getClientOriginalName();
                    $documento->move(public_path('parrilla organica'), $archivo);
                    $file=$request->file('PZA_Archivo');
                    Piezas::create([
                        'PZA_Publicacion_Id' => $request->PZA_Publicacion_Id,
                        'PZA_Url' => Storage::url($file->store('public/parrilla_organica/'))
                    ]);
                } 
            }
        }
        return redirect()->back()->with('mensaje', 'Piezas agregadas con exito');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permisos = ['crear'=> can2('subir-piezas'),'editar'=>can2('editar-piezas'), 'eliminar'=>can2('eliminar-piezas')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $publicaciones = Publicacion::findOrFail($id);  
        $imagenes=DB::table('TBL_Publicacion')
        ->join('TBL_Pieza', 'TBL_Publicacion.id', '=', 'TBL_Pieza.Pza_Publicacion_Id')
        ->select('TBL_Pieza.*')
        ->where('TBL_Publicacion.id',$publicaciones->id)        
        ->oldest()
        ->get();     
         return view('piezas.inicio', compact('datos', 'notificaciones', 'cantidad','permisos','imagenes','publicaciones'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
