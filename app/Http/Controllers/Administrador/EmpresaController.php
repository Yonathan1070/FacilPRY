<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Tablas\Empresas;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = DB::table('TBL_Usuarios as u')
            ->join('TBL_Empresas as e', 'u.USR_Empresa_Id', '=', 'e.id')
            ->where('u.id', '=', session()->get('Usuario_Id'))->first();
        return view('administrador.empresa.editar', compact('datos'));
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
    public function actualizarLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'EMP_Logo_Empresa' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if ($validator->passes()) {
            $usuario = DB::table('TBL_Usuarios as u')
                ->join('TBL_Empresas as e', 'u.USR_Empresa_Id', '=', 'e.id')
                ->where('u.id', '=', session()->get('Usuario_Id'))->first();
            $empresa = Empresas::findOrFail($usuario->USR_Empresa_Id);
            if ($empresa->EMP_Logo_Empresa != null) {
                $ruta = public_path("assets/bsb/images/Logos/".$empresa->EMP_Logo_Empresa);
                unlink($ruta);
            }
            $input = $request->all();
            $nombreArchivo = $input['EMP_Logo_Empresa'] = time() . '.' . $request->EMP_Logo_Empresa->getClientOriginalExtension();
            $request->EMP_Logo_Empresa->move(public_path('assets/bsb/images/Logos'), $input['EMP_Logo_Empresa']);
            Empresas::findOrFail($usuario->USR_Empresa_Id)->update(['EMP_Logo_Empresa' => $nombreArchivo]);
            return response()->json(['success' => 'Logo Actualizado']);
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
        Empresas::findOrFail($request->id)->update($request->all());
        return redirect()->back()->with('mensaje', 'Datos actualizados con exito');
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
