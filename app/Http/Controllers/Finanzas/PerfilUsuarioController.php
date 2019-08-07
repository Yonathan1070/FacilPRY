<?php

namespace App\Http\Controllers\Finanzas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ValidacionUsuario;

class PerfilUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('finanzas.perfil.editar', compact('datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
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
    public function actualizarDatos(ValidacionUsuario $request)
    {
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
