<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Requerimientos, donde se establecen los atributos de la tabla 
 * en la Base de Datos y se realizan las distintas operaciones sobre la
 * misma.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Requerimientos extends Model
{
    protected $table = "TBL_Requerimientos";
    protected $fillable = ['REQ_Nombre_Requerimiento',
        'REQ_Descripcion_Requerimiento',
        'REQ_Proyecto_Id'];
    protected $guarded = ['id'];

    //Función para obtener los requerimientos del proyecto seleccionado
    public static function obtenerRequerimientos($idP)
    {
        $requerimientos = DB::table('TBL_Proyectos as p')
            ->join('TBL_Requerimientos as r', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->where('r.REQ_Proyecto_Id', '=', $idP)
            ->orderBy('r.Id')
            ->get();
        
        return $requerimientos;
    }

    //Funcion para obtener los requerimientos de un proyecto, excepto el requerimiento actual
    public static function obtenerRequerimientosNoActual($request, $idR)
    {
        $requerimientos = Requerimientos::where(
            'REQ_Proyecto_Id', '=', $request['REQ_Proyecto_Id']
        )
            ->where('id', '<>', $idR)
            ->get();
        
        return $requerimientos;
    }
}
