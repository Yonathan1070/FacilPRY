<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Actividades Finalizadas, realiza las distintas consultas
 * que tenga que ver con la tabla Actividades Finalizadas en la
 * Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class ActividadesFinalizadas extends Model
{
    protected $table = "TBL_Actividades_Finalizadas";
    protected $fillable = ['ACT_FIN_Titulo',
        'ACT_FIN_Descripcion',
        'ACT_FIN_Actividad_Id',
        'ACT_FIN_Fecha_Finalizacion',
        'ACT_FIN_Link',
        'ACT_FIN_Revisado'];
    protected $guarded = ['id'];

    //Funcion para obtener las actividades pendientes para aprobar el cliente
    public static function obtenerActividadesAprobar()
    {
        $actividadesPendientes = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->join('TBL_Respuesta as re', 're.RTA_Actividad_Finalizada_Id', '=', 'af.id')
            ->select('af.id as Id_Act_Fin', 'af.*', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 3)
            ->where('re.RTA_Usuario_Id', '=', 0)
            ->where('re.RTA_Estado_Id', '=', 12)
            ->where('af.ACT_FIN_Revisado', '=', 1)
            ->where('p.PRY_Cliente_Id', '=', session()->get('Usuario_Id'))
            ->get();
        
        return $actividadesPendientes;
    }

    //Funcion para obtener las actividades finalizadas del cliente
    public static function obtenerActividadesFinalizadasCliente()
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id as Id_Actividad', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 3)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->get();
        
        return $actividadesFinalizadas;
    }

    //Funcion para obtener las actividades en proceso del cliente
    public static function obtenerActividadesProcesoCliente()
    {
        $actividadesEntregar = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as ea', 'ea.id', '=', 'a.ACT_Estado_Id')
            ->select('a.id as Id_Actividad', 'a.*', 'p.*', 'r.*', 'ea.*')
            ->where('a.ACT_Estado_Id', '=', 1)
            ->where('a.ACT_Trabajador_Id', '=', session()->get('Usuario_Id'))
            ->get();
        
        return $actividadesEntregar;
    }

    //Funcion para crear las actividades finalizadas del cliente
    public static function crearActividadFinalizada($request)
    {
        ActividadesFinalizadas::create([
            'ACT_FIN_Titulo' => $request['ACT_FIN_Titulo'],
            'ACT_FIN_Descripcion' => $request['ACT_FIN_Descripcion'],
            'ACT_FIN_Actividad_Id' => $request['Actividad_Id'],
            'ACT_FIN_Fecha_Finalizacion' => Carbon::now(),
            'ACT_FIN_Revisado' => 1
        ]);
    }

    //Funcion para guardar la entrega de la actividad del perfil de operación
    public static function crearActividadFinalizadaTrabajador($request)
    {
        ActividadesFinalizadas::create([
            'ACT_FIN_Titulo' => $request['ACT_FIN_Titulo'],
            'ACT_FIN_Descripcion' => $request['ACT_FIN_Descripcion'],
            'ACT_FIN_Actividad_Id' => $request['Actividad_Id'],
            'ACT_FIN_Fecha_Finalizacion' => Carbon::now(),
            'ACT_FIN_Link' => $request['ACT_FIN_Link']
        ]);
    }
}
