<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ValidacionUsuario;
use App\Models\Tablas\Notificaciones;

class PerfilUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('perfil.editar', compact('datos', 'notificaciones', 'cantidad'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarFoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'USR_Foto_Perfil' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {
            $usuario = Usuarios::findOrFail(session()->get('Usuario_Id'));
            $ruta = public_path("assets/bsb/images/".$usuario->USR_Foto_Perfil);
            if ($usuario->USR_Foto_Perfil != null) {
                unlink($ruta);
            }
            $input = $request->all();
            $nombreArchivo = $input['USR_Foto_Perfil'] = time() . '.' . $request->USR_Foto_Perfil->getClientOriginalExtension();
            $request->USR_Foto_Perfil->move(public_path('assets/bsb/images'), $input['USR_Foto_Perfil']);
            Usuarios::findOrFail(session()->get('Usuario_Id'))->update(['USR_Foto_Perfil' => $nombreArchivo]);
            return response()->json(['success' => 'Foto Actualizada']);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarDatos(Request $request)
    {
        $usuarios = Usuarios::where('USR_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->where('id', '<>', session()->get('Usuario_Id'))->get();
        foreach ($usuarios as $usuario) {
            if($usuario->USR_Documento_Usuario == $request->USR_Documento_Usuario){
                return redirect()->back()->withErrors('El Documento ya se encuentra en uso.');
            }
            if($usuario->USR_Correo_Usuario == $request->USR_Correo_Usuario){
                return redirect()->back()->withErrors('El correo electrónico ya se encuentra en uso.');
            }
        }
        Usuarios::findOrFail(session()->get('Usuario_Id'))->update($request->all());
        return redirect()->back()->with('mensaje', 'Datos actualizados con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizarClave(Request $request)
    {
        if($request->USR_Clave_Nueva != $request->USR_Clave_Confirmar){
            return redirect()->back()->withErrors('Las contraseñas no coinciden.');
        }
        $clave = Usuarios::select('password')->where('id', '=', session()->get('Usuario_Id'))->first();
        $correcta = Hash::check($request->USR_Clave_Anterior, $clave->password, []);
        if (!$correcta) {
            return redirect()->back()->withErrors('La contraseña antigua es incorrecta.');
        }
        Usuarios::findOrFail(session()->get('Usuario_Id'))->update([
            'password' => bcrypt($request->USR_Clave_Nueva)
        ]);
        return redirect()->back()->with('mensaje', 'Contraseña actualizada.');
    }
}
