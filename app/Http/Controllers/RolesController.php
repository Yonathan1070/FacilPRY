<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionRol;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Roles;
use App\Models\Tablas\Usuarios;
use Illuminate\Database\QueryException;

/**
 * Roles Controller, donde se harán las distintas operaciones sobre la Tabla Roles
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class RolesController extends Controller
{
    /**
     * Muestra la vista con el listado de los roles del sistema
     *
     * @return \Illuminate\View\View Vista del listado de roles
     */
    public function index()
    {
        can('listar-roles');
        
        $permisos = [
            'crear'=> can2('crear-roles'),
            'editar'=>can2('editar-roles'),
            'eliminar'=>can2('eliminar-roles')
        ];

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $roles = Roles::where('id', '<>', '6')->orderBy('id')->get();

        return view(
            'roles.listar',
            compact(
                'roles',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos',
                'asignadas'
            )
        );
    }

    /**
     * Muestra el formulario para crear roles
     *
     * @return \Illuminate\View\View Vista del formulario de crear roles
     */
    public function crear()
    {
        can('crear-roles');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
        return view(
            'roles.crear',
            compact(
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Guarda los 
     *
     * @param  App\Http\Requests\ValidacionRol  $request
     * @return return redirect()->back()->with()
     */
    public function guardar(ValidacionRol $request)
    {
        $roles = Roles::where('RLS_Nombre_Rol', '=', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->first();
        
        if ($roles) {
            return redirect()
                ->back()
                ->withErrors('Ya se encuentra registrado el rol en el sistema')
                ->withInput();
        }
        
        Roles::create([
            'RLS_Rol_Id' => 4,
            'RLS_Nombre_Rol' => $request->RLS_Nombre_Rol,
            'RLS_Descripcion_Rol' => $request->RLS_Descripcion_Rol,
            'RLS_Empresa_Id' => session()->get('Empresa_Id')
        ]);
        
        return redirect()
            ->back()
            ->with('mensaje', 'Rol creado con exito');
    }

    /**
     * Muestra el formulario para editar un rol
     *
     * @param  $id Identificador del rol
     * @return \Illuminate\View\View Vista del formulario de editar roles
     */
    public function editar($id)
    {
        can('editar-roles');
        
        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $asignadas = Actividades::obtenerActividadesProcesoPerfil(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        $rol = Roles::findOrFail($id);
        
        if ($rol->RLS_Rol_Id != 4) {
            return redirect()
                ->back()
                ->withErrors(['El rol es por defecto del sistema, no es posible modificarlo.']);
        }

        return view(
            'roles.editar',
            compact(
                'rol',
                'datos',
                'notificaciones',
                'cantidad',
                'asignadas'
            )
        );
    }

    /**
     * Actualiza los datos del rol en la Base de Datos
     *
     * @param  App\Http\Requests\ValidacionRol  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionRol $request, $id)
    {
        $roles = Roles::where('RLS_Nombre_Rol', '<>', $request->RLS_Nombre_Rol)
            ->where('RLS_Empresa_Id', '=', session()->get('Empresa_Id'))
            ->get();
        foreach ($roles as $rol) {
            if ($rol->RLS_Nombre_Rol==$request->RLS_Nombre_Rol) {
                return redirect()
                    ->back()
                    ->withErrors('Ya se encuentra registrado el rol en el sistema')
                    ->withInput();
            }
        }
        Roles::findOrFail($id)->update($request->all());
        
        return redirect()
            ->route('roles')
            ->with('mensaje', 'Rol actualizado con exito');
    }

    /**
     * Elimina el rol seleccionado
     *
     * @param  $id Identificador del rol
     * @param  \Illuminate\Http\Request  $request
     * @return response()->json()
     */
    public function eliminar(Request $request, $id)
    {
        if (!can('eliminar-roles')) {
            return response()->json(['mensaje' => 'np']);
        } else {
            if ($request->ajax()) {
                $rol = Roles::findOrFail($id);
                if ($rol->RLS_Rol_Id != 4 || $rol->id == 4) {
                    return response()->json(['mensaje' => 'rd']);
                } else {
                    try {
                        Roles::destroy($id);
                        return response()->json(['mensaje' => 'ok']);
                    } catch (QueryException $e) {
                        return response()->json(['mensaje' => 'ng']);
                    }
                }
            }
        }
    }
}