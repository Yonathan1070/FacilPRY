<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Parrilla;
use App\Models\Tablas\Publicacion;
use Illuminate\Support\Facades\DB;

class PublicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
            $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
            $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $parrilla = Parrilla::findOrFail($id);
            return view('publicacion.create', compact('datos', 'notificaciones', 'cantidad','parrilla'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Publicacion::create($request->all());
        return redirect()->back()->with('mensaje', 'Publicacion agregada con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $permisos = ['crear'=> can2('crear-publicacion'),'editar'=>can2('editar-publicacion'), 'eliminar'=>can2('eliminar-publicacion'), 'pieza'=>can2('listar-piezas')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $parrilla = Parrilla::findOrFail($id);       
        $publicaciones= DB::table('TBL_Parrilla')
        ->join('TBL_Publicacion', 'TBL_Parrilla.id', '=', 'TBL_Publicacion.PBL_Parrilla_Id')
        ->select('TBL_Publicacion.*')
        ->where('TBL_Parrilla.id',$id)
        ->oldest()
        ->get();
        return view('publicacion.inicio', compact('datos', 'notificaciones', 'cantidad','permisos','parrilla','publicaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        can('editar-publicacion');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $publicacion = Publicacion::findOrFail($id);
        return view('publicacion.edit', compact('publicacion', 'datos', 'notificaciones', 'cantidad'));
  
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
       Publicacion::findOrFail($id)->update([
        'PBL_Fecha' => $request['PBL_Fecha'],
        'PBL_Publico' => $request['PBL_Copy_General'],
        'PBL_Copy_Pieza'=> $request['PBL_Copy_Pieza'],
        'PBL_Tipo'=> $request['PBL_Tipo'],
        'PBL_Ubicacion'=> $request['PBL_Ubicacion']
    ]);
    return redirect()->route('parrilla')->with('mensaje', 'publicacion actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        if(!can('eliminar-publicacion')){
            return response()->json(['mensaje' => 'np']);
        }else{
            if($request->ajax()){
                    try{
                        Publicacion::destroy($id);
                        return response()->json(['mensaje' => 'ok']);
                    }catch(QueryException $e){
                        return response()->json(['mensaje' => 'ng']);
                    }
                
            }
        }
    }


    public function ver($id){
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));    
        $publicaciones= Publicacion::findOrFail($id);
        $imagenes=DB::table('TBL_Publicacion')
        ->join('TBL_Pieza', 'TBL_Publicacion.id', '=', 'TBL_Pieza.Pza_Publicacion_Id')
        ->select('TBL_Pieza.*')
        ->where('TBL_Publicacion.id',$publicaciones->id)        
        ->oldest()
        ->get();
        $comentarios=DB::table('TBL_Publicacion')
        ->join('TBL_Comentario','TBL_Publicacion.id','=','TBL_Comentario.CMR_Publicacion_Id')
        ->join('TBL_Estados','TBL_Comentario.CMR_Estado_Id','=','TBL_Estados.id')
        ->join('TBL_Usuarios','TBL_Usuarios.id','=','TBL_Comentario.CMR_Usuario_Id')
        ->select('TBL_Comentario.CMR_Comentario', 'TBL_Comentario.created_at as creacion','TBL_Estados.EST_Nombre_Estado','TBL_Usuarios.USR_Nombre_Usuario')
        ->where('TBL_Publicacion.id',$id)
        ->latest('TBL_Comentario.created_at')
        ->get();
        return view('publicacion.ver', compact('datos', 'notificaciones', 'cantidad','publicaciones','imagenes','comentarios'));
    }
}
