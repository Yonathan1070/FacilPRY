<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tablas\Empresas;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Proyectos;
use App\Models\Tablas\Notificaciones;

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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresasActivas = Empresas::obtenerEmpresasActivas();
        $empresasInActivas = Empresas::obtenerEmpresasInactivas();

        return view(
            'empresas.listar',
            compact(
                'empresasActivas',
                'empresasInActivas',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos'
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        return view(
            'empresas.crear',
            compact(
                'datos',
                'notificaciones',
                'cantidad'
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
        Empresas::crearEmpresa($request);

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
        
        return redirect()
            ->back()
            ->with('mensaje', 'Empresa agregada con exito');
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
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $empresa = Empresas::findOrFail($id);
        
        return view(
            'empresas.editar',
            compact(
                'empresa',
                'datos',
                'notificaciones',
                'cantidad'
            )
        );
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
        Empresas::editarEmpresa($request, $id);

        return redirect()
            ->route('empresas')
            ->with('mensaje', 'Empresa actualizada con exito');
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
        if($request->ajax()){
            Empresas::cambiarEstado($id);
            Proyectos::cambiarEstado($id);

            return response()->json(['mensaje' => 'okI']);
        }
    }

    /**
     * Deja activa la empresa
     *
     * @param  $id  Identificador de la empresa
     * @return response()->json()
     */
    public function activar($id)
    {
        Empresas::cambiarEstadoActivado($id);
        Proyectos::cambiarEstadoActivado($id);
        
        return redirect()
            ->back()
            ->with('mensaje', 'Empresa activada satisfactoriamente.');
    }
}
