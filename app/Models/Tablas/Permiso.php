<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Permiso, donde se establecen los atributos de la tabla en la 
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
class Permiso extends Model
{
    protected $table = "TBL_Permiso";
    protected $fillable = [
        'PRM_Nombre_Permiso',
        'PRM_Slug_Permiso'
    ];
    protected $guarded = ['id'];

    public function usuarios(){
        return $this->belongsToMany(
            Usuarios::class,
            'TBL_Permiso_Usuario',
            'PRM_USR_Usuario_Id',
            'PRM_USR_Permiso_Id'
        );
    }

    #Función que obtiene los permisos asignados
    public static function obtenerPermisosAsignados($id)
    {
        $permisoAsignado = DB::table('TBL_Permiso as p')
            ->join(
                'TBL_Permiso_Usuario as pu',
                'pu.PRM_USR_Permiso_Id',
                '=',
                'p.id'
            )->select(
                'p.*'
            )->where(
                'pu.PRM_USR_Usuario_Id', '=', $id
            )->get();
        
        return $permisoAsignado;
    }
}
