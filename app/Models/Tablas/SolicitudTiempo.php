<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Solicitud Teimpo, donde se establecen los atributos de la tabla
 * en la Base de Datos y se realizan las distintas operaciones sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class SolicitudTiempo extends Model
{
    protected $table = "TBL_Solicitud_Tiempo";
    protected $fillable = ['SOL_TMP_Actividad_Id',
        'SOL_TMP_Fecha_Solicitada',
        'SOL_TMP_Estado_Solicitud'];
    public $timestamps = false;
    protected $guarded = ['id'];

    //Funcion que obtiene la actividad de la solicitud de tiempo
    public static function obtenerSolicitudTiempoActividad($idA)
    {
        $solicitud = DB::table('TBL_Solicitud_Tiempo as st')
            ->join('TBL_Actividades as a', 'a.id', '=', 'st.SOL_TMP_Actividad_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->where('a.id', '=', $idA)
            ->where('st.SOL_TMP_Estado_Solicitud', '=', 0)
            ->select('a.id as Id_Actividad', 'st.id as Id_Solicitud', 'st.*', 'a.*', 'u.*')
            ->first();
        
        return $solicitud;
    }

    //Funcion que obtiene la solicitud de tiempo
    public static function obtenerSolicitudTiempo($idS)
    {
        $solicitud = DB::table('TBL_Solicitud_Tiempo as st')
            ->join('TBL_Actividades as a', 'a.id', '=', 'st.SOL_TMP_Actividad_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'a.ACT_Trabajador_Id')
            ->where('st.id', '=', $idS)
            ->where('st.SOL_TMP_Estado_Solicitud', '=', 0)
            ->select('a.id as Id_Actividad', 'st.id as Id_Solicitud', 'st.*', 'a.*', 'u.*')
            ->first();
    }
}
