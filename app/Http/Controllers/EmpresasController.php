<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionEmpresa;
use App\Models\Tablas\Actividades;
use Illuminate\Http\Request;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\Validator;

/**
 * Director Controller, donde se visualizaran y realizaran cambios
 * en la Base de Datos de los directores de protyectos
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class EmpresasController extends Controller
{
    /**
     * Visualiza el listado de las empresas activas o inactivas
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        can('listar-empresas');
        $permisos = [
            'crear'=> can2('crear-empresas'),
            'editar'=>can2('editar-empresas'),
            'eliminar'=>can2('eliminar-empresas'),
            'lUsuarios'=>can2('listar-clientes'),
            'lProyectos'=>can2('listar-proyectos')
        ];

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $empresasActivas = Empresas::obtenerEmpresasActivas();
        $empresasInActivas = Empresas::obtenerEmpresasInactivas();
        //dd($empresasActivas);
        return view(
            'empresas.listar',
            compact(
                'empresasActivas',
                'empresasInActivas',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Visualiza el formulario para crear las empresas
     *
     * @return \Illuminate\View\View Vista de crear empresa
     */
    public function crear()
    {
        can('crear-empresas');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfilHoy(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
        return view(
            'empresas.crear',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda la empresa en la Base de Datos
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect()->back()->with()
     */
    public function guardar(Request $request)
    {
        can('crear-empresas');

        $permisos = [
            'editar'=>can2('editar-empresas'),
            'eliminar'=>can2('eliminar-empresas'),
            'lUsuarios'=>can2('listar-clientes'),
            'lProyectos'=>can2('listar-proyectos')
        ];

        $data = $request->all();
        $validacionEmpresa = new ValidacionEmpresa();
        $validator = Validator::make($data, $validacionEmpresa->rules(null), $validacionEmpresa->messages());

        if($validator->passes()){
            $empresa = Empresas::crearEmpresa($request);

            $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
            
            if($datos->USR_Supervisor_Id == 0)
                $datos->USR_Supervisor_Id = 1;
            
            Notificaciones::crearNotificacion(
                $datos->USR_Nombres_Usuario.
                    ' '.
                    $datos->USR_Apellidos_Usuario.
                    ' ha creado la empresa '.
                    $request->EMP_Nombre_Empresa,
                session()->get('Usuario_Id'),
                $datos->USR_Supervisor_Id,
                'empresas',
                null,
                null,
                'person_add'
            );
            
            return response()->json(['empresa' => $empresa, 'permisos' => $permisos, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Muestra el formulario para editar la empresa
     *
     * @param  $id  Identificador de la empresa
     * @return \Illuminate\View\View Vista de editar empresa
     */
    public function editar($id)
    {
        can('editar-empresas');
        
        $empresa = Empresas::findOrFail($id);

        return response()->json(['empresa' => $empresa]);
    }

    /**
     * Actualiza los datos de la empresa
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador de la empresa
     * @return redirect()->route()
     */
    public function actualizar(Request $request, $id)
    {
        can('editar-empresas');

        $permisos = [
            'editar'=>can2('editar-empresas'),
            'eliminar'=>can2('eliminar-empresas'),
            'lUsuarios'=>can2('listar-clientes'),
            'lProyectos'=>can2('listar-proyectos')
        ];

        $data = $request->all();
        $validacionEmpresa = new ValidacionEmpresa();
        $validator = Validator::make($data, $validacionEmpresa->rules($id), $validacionEmpresa->messages());

        if($validator->passes()){
            Empresas::editarEmpresa($request, $id);
            $empresa = Empresas::findOrFail($id);

            return response()->json(['empresa' => $empresa, 'permisos' => $permisos, 'mensaje' => 'ok']);
        } else {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
    }

    /**
     * Deja inactiva la empresa
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id  Identificador de la empresa
     * @return response()->json()
     */
    public function inactivar(Request $request, $id)
    {
        if (can('editar-empresas')) {
            if($request->ajax()){
                Empresas::cambiarEstado($id);
                Proyectos::cambiarEstado($id);
    
                return response()->json(['mensaje' => 'okI']);
            }
        } else {
            return abort(404);
        }
        
    }

    /**
     * Deja activa la empresa
     *
     * @param  $id  Identificador de la empresa
     * @return response()->json()
     */
    public function activar(Request $request, $id)
    {
        if (can('editar-empresas')) {
            if($request->ajax()){
                Empresas::cambiarEstadoActivado($id);
                Proyectos::cambiarEstadoActivado($id);
    
                return response()->json(['mensaje' => 'ok']);
            }
        } else {
            return abort(404);
        }
    }
}
