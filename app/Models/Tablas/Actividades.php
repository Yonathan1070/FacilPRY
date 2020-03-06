<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Actividades, realiza las distintas consultas que tenga que 
 * ver con la tabla Actividades en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Actividades extends Model
{
    protected $table = "TBL_Actividades";
    protected $fillable = ['ACT_Nombre_Actividad', 
        'ACT_Descripcion_Actividad',
        'ACT_Estado_Id',
        'ACT_Fecha_Inicio_Actividad',
        'ACT_Fecha_Fin_Actividad',
        'ACT_Costo_Estimado_Actividad',
        'ACT_Costo_Real_Actividad',
        'ACT_Requerimiento_Id',
        'ACT_Trabajador_Id',
        'ACT_Encargado_Id'];
    protected $guarded = ['id'];

    //Función para obtener las actividades y las horas estimadas de cada una
    public static function obtenerActividadesHoras($id)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'ha.*',
                'a.*'
            )
            ->where('p.id', '=', $id)
            ->groupBy('HRS_ACT_Actividad_Id')->get();
        return $actividades;
    }

    //Función para otener las horas de trabajo asignadas para cada actividad por trabajador
    public static function obtenerHorasActividadesTrabajador($id)
    {
        $horasActividades = DB::table('TBL_Horas_Actividad as ha')
            ->join('TBL_Actividades as a', 'a.id', '=', 'ha.HRS_ACT_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'ha.*',
                'a.*'
            )
            ->where('uu.id', '=', $id)
            ->groupBy('HRS_ACT_Actividad_Id')->get();
        return $horasActividades;
    }

    //Función para obtener las actividades por proyecto
    public static function obtenerActividadesProyecto($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('REQ_Proyecto_Id', '=', $id)
            ->get();
        
        return $actividades;
    }

    //Función que obtiene las actividades del usuario
    public static function obtenerActividades($idR, $cliente)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '=', $idR)
            ->where('a.ACT_Trabajador_Id', '<>', $cliente->PRY_Cliente_Id)
            ->select(
                'a.id as ID_Actividad',
                'r.id as ID_Requerimiento',
                'a.*',
                'u.*',
                'e.*',
                'r.*'
            )
            ->orderBy('a.Id', 'ASC')
            ->get();
        return $actividades;
    }

    //Funcion que obtiene las actividades excepto la que se esta editando
    public static function obtenerActividadesNoActual($idP, $idA)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->where('REQ_Proyecto_Id', '=', $idP)
            ->where('a.id', '<>', $idA)
            ->select('a.id as Actividad_Id', 'a.*', 'r.*', 'p.*')
            ->get();
        
        return $actividades;
    }

    //Función que obtiene las actividades para generar el PDF
    public static function obtenerActividadesPDF($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Usuarios as us', 'us.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Estados as es', 'es.id', '=', 'a.ACT_Estado_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'us.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->where('ro.id', '<>', 3)
            ->where('p.id', '=', $id)
            ->select(
                'a.*',
                'us.USR_Nombres_Usuario as NombreT',
                'us.USR_Apellidos_Usuario as ApellidoT',
                'p.*',
                'r.*',
                'u.*',
                'es.*',
                'e.*'
            )
            ->get();
        
        return $actividades;
    }

    //Función para obtener las actividades por proyecto
    public static function obtenerActividadesTotales($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '<>', 3)
            ->where('p.id', '=', $id)
            ->get();
        return $actividadesTotales;
    }

    //Función para obtener las actividades de cada trabajador
    public static function obtenerActividadesTotalesTrabajador($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '<>', 3)
            ->where('uu.id', '=', $id)
            ->get();
        return $actividadesTotales;
    }

    //Funcion para obtener las actividaades de cada requerimiento
    public static function obtenerActividadesTotalesRequerimiento($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->where('r.id', '=', $id)
            ->get();
        
        return $actividadesTotales;
    }

    //Funcion para obtener las actividades finalizadas por proyecto
    public static function obtenerActividadesFinalizadas($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.id', '<>', 1)
            ->where('e.id', '<>', 2)
            ->where('r.id', '<>', 6)
            ->where('p.id', '=', $id)
            ->get();
        return $actividadesFinalizadas;
    }

    //Funcion para obtener las actividades finalizadas por trabajador
    public static function obtenerActividadesFinalizadasTrabajador($id){
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as uu', 'uu.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'uu.id')
            ->join('TBL_Roles as r', 'r.id', '=', 'ur.USR_RLS_Rol_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.id', '<>', 1)
            ->where('e.id', '<>', 2)
            ->where('r.id', '<>', 3)
            ->where('uu.id', '=', $id)
            ->get();
        return $actividadesFinalizadas;
    }

    //Función para obtener las actividades finalizadas por requerimiento
    public static function obtenerActividadesFinalizadasRequerimiento($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('e.EST_Nombre_Estado', '<>', 'En Proceso')
            ->where('e.EST_Nombre_Estado', '<>', 'Atrasado')
            ->where('e.EST_Nombre_Estado', '<>', 'Rechazado')
            ->where('r.id', '=', $id)
            ->get();
        
        return $actividadesFinalizadas;
    }

    //Funcion para obtener las actividades del cliente
    public static function obtenerActividadesCliente($idR, $cliente){
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.Id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.Id', 'a.ACT_Trabajador_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('r.id', '=', $idR)
            ->where('a.ACT_Trabajador_Id', '=', $cliente->PRY_Cliente_Id)
            ->select(
                'a.id as ID_Actividad',
                'r.id as ID_Requerimiento',
                'a.*',
                'u.*',
                'e.*',
                'r.*'
            )
            ->orderBy('a.Id', 'ASC')
            ->get();
        return $actividades;
    }

    //Función para obtener las actividades pendientes
    public static function obtenerActividadPendiente($id)
    {
        $actividadPendiente = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join('TBL_Requerimientos as re', 're.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 're.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->select('af.id as Id_Act_Fin', 'a.id as Id_Act', 'af.*', 'a.*', 'p.*', 're.*', 'u.*', 'ro.*')
            ->where('af.Id', '=', $id)
            ->orderByDesc('af.created_at')
            ->first();
        
        return $actividadPendiente;
    }

    //Función para obtener los detalles de la actividad
    public static function obtenerDetalleActividad($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Empresas as em', 'em.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Estados as e', 'e.id', '=', 'a.ACT_Estado_Id')
            ->where('a.id', '=', $id)
            ->first();
        
        return $actividad;
    }

    //Función para guardar la actividad en la Base de Datos
    public static function crearActividad($request, $idR, $idUsuario){
        Actividades::create([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' =>
                $request['ACT_Fecha_Fin_Actividad'] . ' ' . $request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Requerimiento_Id' => $idR,
            'ACT_Trabajador_Id' => $idUsuario,
            'ACT_Encargado_Id' => session()->get('Usuario_Id')
        ]);
    }

    //Función para actualizar los datos de la Actividad
    public static function actualizarActividad($request, $idA, $idUsuario)
    {
        $actividad = Actividades::findOrFail($idA)->update([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].
                ' '.
                $request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Trabajador_Id' => $idUsuario,
        ]);

        return $actividad;
    }

    //Funcion para actualizar el requerimiento de la actividad
    public static function actualizarRequerimientoActividad($idA, $request)
    {
        Actividades::findOrFail($idA)
            ->update(['ACT_Requerimiento_Id' => $request['ACT_Requerimiento']]);
    }

    //Funcion para actualizar la fechaa de finalización de la actividad
    public static function actualizarFechaFin($solicitud)
    {
        Actividades::findOrFail($solicitud->Id_Actividad)->update([
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Fin_Actividad' => $solicitud->SOL_TMP_Fecha_Solicitada
        ]);
    }
}