<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Menu, donde se establecen los atributos de la tabla en la 
 * Base de Datos y se realizan las distintas operaciones sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Menu extends Model
{
    protected $table = "TBL_Menu";
    protected $fillable = [
        'MN_Nombre_Menu',
        'MN_Nombre_Ruta_Menu',
        'MN_Icono_Menu'
    ];
    protected $guarded = ['id'];

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuarios::class,
            'TBL_Menu_Usuario',
            'MN_USR_Usuario_Id',
            'MN_USR_Menu_Id'
        );
    }

    #Funcion que obtiene los items hijos
    public function getHijos($padres, $line){
        $children = [];
        foreach($padres as $line1){
            if($line['id']==$line1['MN_Menu_Id']){
                $children = array_merge(
                    $children,
                    [array_merge($line1, ['submenu'=>$this->getHijos($padres, $line1)])]
                );
            }
        }
        return $children;
    }

    #Función que obtiene los items padres
    public function getPadres($front)
    {
        if($front) {
            return $this->whereHas('usuarios', function($query) {
                $query->where('MN_USR_Usuario_Id', session()->get('Usuario_Id'))
                    ->orderby('MN_Menu_Id');
            })->orderby('MN_Menu_Id')
                ->orderby('MN_Orden_Menu')
                ->get()
                ->toArray();
        } else {
            return $this->orderby('MN_Menu_Id')
                ->orderby('MN_Orden_Menu')
                ->get()
                ->toArray();
        }
    }

    #Función que crea y ordena el menú con respecto a los items
    public static function getMenu($front =false)
    {
        $menus = new Menu();
        $padres = $menus->getPadres($front);
        $menuAll = [];
        foreach($padres as $line) {
            if($line['MN_Menu_Id']!=0)
                break;
            $item = [array_merge($line, ['submenu'=>$menus->getHijos($padres, $line)])];
            $menuAll=array_merge($menuAll, $item);
        }
        return $menuAll;
    }

    #Función que guarda el orden en que se debe mostrar el menú
    public function guardarOrden($menu)
    {
        $menus=json_decode($menu);
        foreach ($menus as $var => $value) {
            $this->where('id', $value->id)
                ->update([
                    'MN_Menu_Id' => 0,
                    'MN_Orden_Menu' => $var + 1
                ]);
        }
    }

    #Función que obtiene los items asignados
    public static function obtenerItemsAsignados($id)
    {
        $menuAsignado = DB::table('TBL_Menu as m')
            ->join(
                'TBL_Menu_Usuario as mu',
                'mu.MN_USR_Menu_Id',
                '=',
                'm.id'
            )->select(
                'm.*'
            )->where(
                'mu.MN_USR_Usuario_Id', '=', $id
            )->get();
        
        return $menuAsignado;
    }
}
