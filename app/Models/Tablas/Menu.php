<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "TBL_Menu";
    protected $fillable = ['MN_Mombre_Menu', 'MN_Nombre_Ruta_Menu', 'MN_Icono_Menu'];
    protected $guarded = ['id'];

    public function roles(){
        return $this->belongsToMany(Roles::class, 'TBL_Menu_Rol', 'MN_RL_Rol_Id', 'MN_RL_Menu_Id')->withPivot('MN_RL_Rol_Id', 'MN_RL_Menu_Id');
    }

    public function getHijos($padres, $line){
        $children = [];
        foreach($padres as $line1){
            if($line['id']==$line1['MN_Menu_Id']){
                $children = array_merge($children, [array_merge($line1, ['submenu'=>$this->getHijos($padres, $line1)])]);
            }
        }
        return $children;
    }
    public function getPadres($front){
        if($front){
            return $this->whereHas('roles', function($query){
                $query->where('MN_RL_Rol_Id', session()->get('Rol_Id'))->orderby('MN_Menu_Id');
            })->orderby('MN_Menu_Id')
                ->orderby('MN_Orden_Menu')
                ->get()
                ->toArray();
        }else{
            return $this->orderby('MN_Menu_Id')
                ->orderby('MN_Orden_Menu')
                ->get()
                ->toArray();
        }
    }

    public static function getMenu($front =false){
        $menus = new Menu();
        $padres = $menus->getPadres($front);
        $menuAll = [];
        foreach($padres as $line){
            if($line['MN_Menu_Id']!=0)
                break;
            $item = [array_merge($line, ['submenu'=>$menus->getHijos($padres, $line)])];
            $menuAll=array_merge($menuAll, $item);
        }
        return $menuAll;
    }

    public function guardarOrden($menu){
        $menus=json_decode($menu);
        foreach ($menus as $var => $value) {
            $this->where('id', $value->id)->update(['MN_Menu_Id' => 0, 'MN_Orden_Menu' => $var + 1]);
            if(!empty($value->children)){
                foreach ($value->children as $key => $vchild) {
                    $update_id = $vchild->id;
                    $parent_id = $value->id;
                    $this->where('id', $update_id)->update(['MN_Menu_Id' => $parent_id, 'MN_Orden_Menu' => $key + 1]);

                    if (!empty($vchild->children)) {
                        foreach ($vchild->children as $key => $vchild1) {
                            $update_id = $vchild1->id;
                            $parent_id = $vchild->id;
                            $this->where('id', $update_id)->update(['MN_Menu_Id' => $parent_id, 'MN_Orden_Menu' => $key + 1]);

                            if (!empty($vchild1->children)) {
                                foreach ($vchild1->children as $key => $vchild2) {
                                    $update_id = $vchild2->id;
                                    $parent_id = $vchild1->id;
                                    $this->where('id', $update_id)->update(['MN_Menu_Id' => $parent_id, 'MN_Orden_Menu' => $key + 1]);

                                    if (!empty($vchild2->children)) {
                                        foreach ($vchild2->children as $key => $vchild3) {
                                            $update_id = $vchild3->id;
                                            $parent_id = $vchild2->id;
                                            $this->where('id', $update_id)->update(['MN_Menu_Id' => $parent_id, 'MN_Orden_Menu' => $key + 1]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
