<?php

namespace App\Http\Controllers\Administrador;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionMenu;
use App\Models\Tablas\Menu;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Usuarios;

/**
 * Menu Controller, donde se harán las distintas operaciones de base de datos sobre la tabla Menu
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class MenuController extends Controller
{
    /**
     * Muestra la lista de los item del menú actuales
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function inicio()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        $menus = Menu::getMenu();
        return view(
            'administrador.menu.listar',
            compact(
                'notificaciones',
                'cantidad',
                'datos',
                'menus'
            )
        );
    }

    /**
     * Muestra el formulario para crear el item del menú
     *
     * @return \Illuminate\View\View Vista para crear el item
     */
    public function crear()
    {
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        
        return view(
            'administrador.menu.crear',
            compact(
                'notificaciones',
                'cantidad',
                'datos'
            )
        );
    }

    /**
     * Guarda en la base de datos la información del item de menú
     *
     * @param  App\Http\Requests\ValidacionMenu $request
     * @return redirect()->back()->with()
     */
    public function guardar(ValidacionMenu $request)
    {
        Menu::create($request->all());
        
        return redirect()
            ->back()
            ->with('mensaje', 'Menú creado con exito');
    }

    /**
     * Actualiza el orden de la visualización del menú
     *
     * @param  \Illuminate\Http\Request  $request
     * @return return response()->json()
     */
    public function guardarOrden(Request $request)
    {
        if($request->ajax()){
            $menu = new Menu;
            $menu->guardarOrden($request->menu);
            
            return response()
                ->json(['mensaje' => 'ok']);
        }else{
            abort(404);
        }
    }

    /**
     * Muestra el formulari para editar el item del menú
     *
     * @param  int  $id
     * @return \Illuminate\View\View Vista para editar el item
     */
    public function editar($id)
    {
        $menu = Menu::findOrFail($id);
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $notificaciones = Notificaciones::obtenerNotificaciones(session()->get('Usuario_Id'));
        $cantidad = Notificaciones::obtenerCantidadNotificaciones(session()->get('Usuario_Id'));
        
        return view(
            'administrador.menu.editar',
            compact(
                'notificaciones',
                'cantidad',
                'datos',
                'menu'
            )
        );
    }

    /**
     * Actualiza los datos del item en la base de datos
     *
     * @param  App\Http\Requests\ValidacionMenu $request
     * @param  $id Identificador del item
     * @return redirect()->back()->with()
     */
    public function actualizar(ValidacionMenu $request, $id)
    {
        Menu::findOrFail($id)->update($request->all());
        
        return redirect()
            ->route('menu')
            ->with('mensaje', 'El Menú ha sido actualizado');
    }

    /**
     * Elimina el item seleccionado
     *
     * @param  $id identificador del item
     * @return redirect()->route('named_route')->with()
     */
    public function eliminar($id)
    {
        Menu::findOrFail($id)->delete();
        
        return redirect()
            ->route('menu')
            ->with('mensaje', 'El Menú ha sido eliminado');
    }
}