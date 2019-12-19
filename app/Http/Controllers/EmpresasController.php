<?php

namespace App\Http\Controllers;

use App\Models\Tablas\Empresas;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-empresas');
        $permisos = ['crear'=> can2('crear-empresas'), 'editar'=>can2('editar-empresas'), 'eliminar'=>can2('eliminar-empresas'), 'lUsuarios'=>can2('listar-clientes'), 'lProyectos'=>can2('listar-proyectos')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresas = DB::table('TBL_Empresas')->where('EMP_Empresa_Id', '=', session()->get('Empresa_Id'))->get();

        return view('empresas.listar', compact('empresas', 'datos', 'notificaciones', 'cantidad', 'permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear()
    {
        can('crear-empresas');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        return view('empresas.crear', compact('datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        Empresas::crearEmpresa($request);

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        if($datos->USR_Supervisor_Id == 0)
            $datos->USR_Supervisor_Id = 1;
        Notificaciones::crearNotificacion(
            $datos->USR_Nombres_Usuario . ' ' . $datos->USR_Apellidos_Usuario . ' ha creado la empresa ' . $request->EMP_Nombre_Empresa,
            session()->get('Usuario_Id'),
            $datos->USR_Supervisor_Id,
            'empresas',
            null,
            null,
            'person_add'
        );
        
        return redirect()->back()->with('mensaje', 'Empresa agregada con exito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        can('editar-empresas');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        return view('empresas.editar', compact('empresa', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request, $id)
    {
        Empresas::editarEmpresa($request, $id);

        return redirect()->route('empresas')->with('mensaje', 'Empresa actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        //
    }
}
