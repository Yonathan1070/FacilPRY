<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionRequerimiento;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Requerimientos;
use App\Models\Tablas\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequerimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($idP)
    {
        can('listar-requerimientos');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $requerimientos = DB::table('TBL_Proyectos as p')
            ->join('TBL_Requerimientos as r', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->where('r.REQ_Proyecto_Id', '=', $idP)
            ->orderBy('r.Id')
            ->get();
        $proyecto = Proyectos::findOrFail($idP);
        return view('requerimientos.listar', compact('requerimientos', 'proyecto', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear($idP)
    {
        can('crear-requerimientos');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        return view('requerimientos.crear', compact('proyecto', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionRequerimiento $request)
    {
        $datosU = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $request->REQ_Proyecto_Id)
            ->first();
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $request['REQ_Proyecto_Id'])->get();
        foreach ($requerimientos as $requerimiento) {
            if ($requerimiento->REQ_Nombre_Requerimiento == $request['REQ_Nombre_Requerimiento']) {
                return redirect()->back()->withErrors('El requerimiento ya se encuentra registrado para este proyecto.')->withInput();
            }
        }
        Requerimientos::create($request->all());
        Notificaciones::crearNotificacion(
            'Hola! ' . $datosU->USR_Nombres_Usuario . ' ' . $datosU->USR_Apellidos_Usuario . ', se han agregado requerimientos a su proyecto.',
            session()->get('Usuario_Id'),
            $datosU->id,
            'inicio_cliente',
            null,
            null,
            'library_add'
        );
        return redirect()->route('crear_requerimiento', [$request['REQ_Proyecto_Id']])->with('mensaje', 'Requerimiento agregado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mostrar($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($idP, $idR)
    {
        can('editar-requerimientos');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = Proyectos::findOrFail($idP);
        $requerimiento = Requerimientos::findOrFail($idR);
        return view('requerimientos.editar', compact('proyecto', 'requerimiento', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionRequerimiento $request, $idR)
    {
        $datosU = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $request->REQ_Proyecto_Id)
            ->first();
        $requerimientos = Requerimientos::where('REQ_Proyecto_Id', '=', $request['REQ_Proyecto_Id'])
            ->where('id', '<>', $idR)->get();
        foreach ($requerimientos as $requerimiento) {
            if ($requerimiento->REQ_Nombre_Requerimiento == $request['REQ_Nombre_Requerimiento']) {
                return redirect()->back()->withErrors('El requerimiento ya se encuentra registrado para este proyecto.')->withInput();
            }
        }
        Requerimientos::findOrFail($idR)->update($request->all());
        Notificaciones::crearNotificacion(
            'Hola! ' . $datosU->USR_Nombres_Usuario . ' ' . $datosU->USR_Apellidos_Usuario . ', se ha editado un requerimiento de su proyecto.',
            session()->get('Usuario_Id'),
            $datosU->id,
            'inicio_cliente',
            null,
            null,
            'update'
        );
        return redirect()->route('requerimientos', [$request['REQ_Proyecto_Id']])->with('mensaje', 'Requerimiento actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request, $idP, $idR)
    {
        if (!can('eliminar-requerimientos')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                try {
                    $datosU = DB::table('TBL_Proyectos as p')
                        ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
                        ->where('p.id', '=', $idP)
                        ->first();
                    Requerimientos::destroy($idR);
                    Notificaciones::crearNotificacion(
                        'Hola! ' . $datosU->USR_Nombres_Usuario . ' ' . $datosU->USR_Apellidos_Usuario . ', se ha eliminado un requerimiento de su proyecto.',
                        session()->get('Usuario_Id'),
                        $datosU->id,
                        'inicio_cliente',
                        null,
                        null,
                        'delete_forever'
                    );
                    return response()->json(['mensaje' => 'ok']);
                } catch (QueryException $e) {
                    return response()->json(['mensaje' => 'ng']);
                }
            }
        }
    }
}
