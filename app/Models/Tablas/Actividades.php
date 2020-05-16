<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
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

    #Función para obtener las actividades y las horas estimadas de cada una
    public static function obtenerActividadesHoras($id)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'ha.HRS_ACT_Actividad_Id'
            )->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'ha.*',
                'a.*'
            )->where(
                'p.id', '=', $id
            )->where(
                'a.ACT_Fecha_Fin_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->groupBy(
                'HRS_ACT_Actividad_Id'
            )->get();
        
        return $actividades;
    }

    #Función para obtener las actividades y las horas estimadas de cada una
    public static function obtenerActividadesHorasPRY_TRB($id, $idTrabajador)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'ha.HRS_ACT_Actividad_Id'
            )->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'ha.*',
                'a.*'
            )->where(
                'u.id', '=', $idTrabajador
            )->where(
                'p.id', '=', $id
            )->where(
                'a.ACT_Fecha_Inicio_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->groupBy(
                'HRS_ACT_Actividad_Id'
            )->get();
        
        return $actividades;
    }

    #Función para otener las horas de trabajo asignadas para cada actividad por trabajador
    public static function obtenerHorasActividadesTrabajador($id)
    {
        $horasActividades = DB::table('TBL_Horas_Actividad as ha')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'ha.HRS_ACT_Actividad_Id'
            )->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'ha.*',
                'a.*'
            )->where(
                'uu.id', '=', $id
            )->where(
                'a.ACT_Fecha_Fin_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->groupBy(
                'HRS_ACT_Actividad_Id'
            )->get();
        
        return $horasActividades;
    }

    #Función para obtener las actividades por proyecto
    public static function obtenerActividadesProyecto($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->where(
                'REQ_Proyecto_Id', '=', $id
            )->get();
        
        return $actividades;
    }

    #Función que obtiene las actividades del usuario
    public static function obtenerActividades($idR, $cliente)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'ha.HRS_ACT_Actividad_Id'
            )->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.Id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.Id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->leftJoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->where(
                'r.id', '=', $idR
            )->where(
                'a.ACT_Trabajador_Id', '<>', $cliente->PRY_Cliente_Id
            )->select(
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'a.id as ID_Actividad',
                'r.id as ID_Requerimiento',
                'a.*',
                'u.*',
                'e.*',
                'r.*',
                'af.*'
            )->orderBy(
                'a.Id', 'ASC'
            )->groupBy(
                'a.Id'
            )->get();
        
        return $actividades;
    }

    #Funcion que obtiene las actividades excepto la que se esta editando
    public static function obtenerActividadesNoActual($idP, $idA)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->where(
                'REQ_Proyecto_Id',
                '=',
                $idP
            )->where(
                'a.id',
                '<>',
                $idA
            )->select(
                'a.id as Actividad_Id',
                'a.*',
                'r.*',
                'p.*'
            )->get();
        
        return $actividades;
    }

    #Función que obtiene las actividades para generar el PDF
    public static function obtenerActividadesPDF($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Usuarios as us',
                'us.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Estados as es',
                'es.id',
                '=',
                'a.ACT_Estado_Id'
            )->join(
                'TBL_Empresas as e',
                'e.id',
                '=',
                'u.USR_Empresa_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'us.id'
            )->join(
                'TBL_Roles as ro',
                'ro.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->where(
                'ro.id', '<>', 3
            )->where(
                'p.id', '=', $id
            )->select(
                'a.*',
                'us.USR_Nombres_Usuario as NombreT',
                'us.USR_Apellidos_Usuario as ApellidoT',
                'p.*',
                'r.*',
                'u.*',
                'es.*',
                'e.*'
            )->get();
        
        return $actividades;
    }

    #Función para obtener las actividades por proyecto
    public static function obtenerActividadesTotales($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'r.RLS_Rol_Id', '>', 3
            )->where(
                'p.id', '=', $id
            )->where(
                'a.ACT_Fecha_Fin_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividadesTotales;
    }

        #Función para obtener las actividades por proyecto
        public static function obtenerActividadesTotalesPRY_TRB($id, $idTrabajador)
        {
            $actividadesTotales = DB::table('TBL_Actividades as a')
                ->join(
                    'TBL_Requerimientos as re',
                    're.id',
                    '=',
                    'a.ACT_Requerimiento_Id'
                )->join(
                    'TBL_Proyectos as p',
                    'p.id',
                    '=',
                    're.REQ_Proyecto_Id'
                )->join(
                    'TBL_Usuarios as uu',
                    'uu.id',
                    '=',
                    'a.ACT_Trabajador_Id'
                )->join(
                    'TBL_Usuarios_Roles as ur',
                    'ur.USR_RLS_Usuario_Id',
                    '=',
                    'uu.id'
                )->join(
                    'TBL_Roles as r',
                    'r.id',
                    '=',
                    'ur.USR_RLS_Rol_Id'
                )->join(
                    'TBL_Estados as e',
                    'e.id',
                    '=',
                    'a.ACT_Estado_Id'
                )->where(
                    'r.id', '>', 3
                )->where(
                    'p.id', '=', $id
                )->where(
                    'uu.id', '=', $idTrabajador
                )->where(
                    'a.ACT_Fecha_Inicio_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
                )->get();
            
            return $actividadesTotales;
        }

    #Función para obtener las actividades de cada trabajador
    public static function obtenerActividadesTotalesTrabajador($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'r.id', '>', 3
            )->where(
                'uu.id', '=', $id
            )->where(
                'a.ACT_Fecha_Fin_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->get();
        
        return $actividadesTotales;
    }

    #Funcion para obtener las actividaades de cada requerimiento
    public static function obtenerActividadesTotalesRequerimiento($id)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->where(
                'r.id', '=', $id
            )->get();
        
        return $actividadesTotales;
    }

    #Funcion para obtener la actividad seleccionada
    public static function obtenerActividad($id, $idUsuario)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->select('a.*')
            ->where('a.ACT_Trabajador_Id', '=', $idUsuario)
            ->where('a.id', '=', $id)
            ->first();
        
        return $actividades;
    }

    #Funcion para obtener las actividades finalizadas por proyecto
    public static function obtenerActividadesFinalizadas($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'r.RLS_Rol_Id', '>', 3
            )->where(
                'p.id', '=', $id
            )->where(
                'e.id', '!=', 1
            )->where(
                'e.id', '!=', 2
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Funcion para obtener las actividades finalizadas por proyecto
    public static function obtenerActividadesFinalizadasPRY_TRB($id, $idTrabajador)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'e.id', '<>', 1
            )->where(
                'e.id', '<>', 2
            )->where(
                'r.id', '>', 3
            )->where(
                'uu.id', '=', $idTrabajador
            )->where(
                'p.id', '=', $id
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Funcion para obtener las actividades finalizadas por trabajador
    public static function obtenerActividadesFinalizadasTrabajador($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'uu.id'
            )->join(
                'TBL_Roles as r',
                'r.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'e.id', '<>', 1
            )->where(
                'e.id', '<>', 2
            )->where(
                'r.id', '>', 3
            )->where(
                'uu.id', '=', $id
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Función para obtener las actividades finalizadas por requerimiento
    public static function obtenerActividadesFinalizadasRequerimiento($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'e.EST_Nombre_Estado', '<>', 'En Proceso'
            )->where(
                'e.EST_Nombre_Estado', '<>', 'Atrasado'
            )->where(
                'e.EST_Nombre_Estado', '<>', 'Rechazado'
            )->where(
                'r.id', '=', $id
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Funcion para obtener las actividades del cliente
    public static function obtenerActividadesCliente($idR, $cliente)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.Id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.Id',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->where(
                'r.id', '=', $idR
            )->where(
                'a.ACT_Trabajador_Id', '=', $cliente->PRY_Cliente_Id
            )->select(
                'a.id as ID_Actividad',
                'r.id as ID_Requerimiento',
                'a.*',
                'u.*',
                'e.*',
                'r.*'
            )->orderBy(
                'a.Id', 'ASC'
            )->get();
        
        return $actividades;
    }

    #Función para obtener las actividades pendientes
    public static function obtenerActividadPendiente($id)
    {
        $actividadPendiente = DB::table('TBL_Actividades_Finalizadas as af')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'af.ACT_FIN_Actividad_Id'
            )->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Roles as ro',
                'ro.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->select(
                'af.id as Id_Act_Fin',
                'a.id as Id_Act',
                'af.*',
                'a.*',
                'p.*',
                're.*',
                'u.*',
                'ro.*'
            )->where(
                'af.Id', '=', $id
            )->orderByDesc(
                'af.created_at'
            )->first();
        
        return $actividadPendiente;
    }

    #Función para obtener los detalles de la actividad
    public static function obtenerDetalleActividad($id)
    {
        $actividad = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'p.PRY_Empresa_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.*',
                'r.*',
                'p.*',
                'em.*',
                'e.*',
                'a.id as Id_Actividad'
            )->where(
                'a.id', '=', $id
            )->first();
        
        return $actividad;
    }

    #Función para obtener el detalle de la actividad
    public static function obtenerActividadDetalle($id)
    {
        $actividadPendiente = DB::table('TBL_Actividades_Finalizadas as af')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'af.ACT_FIN_Actividad_Id'
            )->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Roles as ro',
                'ro.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )->select(
                'af.id as Id_Act_Fin',
                'a.id as Id_Act',
                'af.*',
                'a.*',
                'p.*',
                're.*',
                'u.*',
                'ro.*'
            )->where(
                'a.id', '=', $id
            )->orderByDesc(
                'af.created_at'
            )->first();
        
        return $actividadPendiente;
    }

    #Función para obtener las actividades pendientes de cobro
    public static function obtenerActividadesCobrar()
    {
        $cobros = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_Fin_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Respuesta as re',
                're.RTA_Actividad_Finalizada_Id',
                '=',
                'af.id'
            )->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                're.RTA_Estado_Id'
            )->select(
                'a.id as Id_Actividad',
                'u.id as Id_Cliente',
                'a.*',
                'u.*',
                'p.*'
            )->where(
                'e.id', '=', 7
            )->orderBy(
                'p.id'
            )->get();
        
        return $cobros;
    }

    #Funcion para obtener actividades pendientes por asignar costos
    public static function obtenerActividadesAsignarCosto()
    {
        $cobros = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.id as Id_Actividad',
                'u.id as Id_Cliente',
                'a.*',
                'u.*',
                'p.*'
            )->where(
                'e.id', '=', 8
            )->where(
                'a.ACT_Costo_Estimado_Actividad', '<>', 0
            )->orderBy(
                'p.id'
            )->get();
        
        return $cobros;
    }

    #Función para obtener las actividades pendientes de pago
    public static function obtenerActividadesPendientesPago($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->where(
                'a.ACT_Estado_Id', '=', 9
            )->where(
                'u.id', '=', $id
            )->select(
                'a.id'
            )->get();

        return $actividades;
    }

    #Funcion para obtener las actividades en transaccion pendiente
    public static function obtenerTransaccionPendiente($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->where(
                'a.ACT_Estado_Id', '=', 14
            )->where(
                'u.id', '=', $id
            )->select(
                'a.id'
            )->get();

        return $actividades;
    }

    #Funcion para obtener las actividades en proceso del perfil de operación
    public static function obtenerActividadesProcesoPerfil($id)
    {
        $actividadesProceso = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'PRY_Empresa_Id'
            )->leftjoin(
                'TBL_Horas_Actividad as ha',
                'ha.HRS_ACT_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->leftJoin(
                'TBL_Documentos_Soporte as ds',
                'ds.DOC_Actividad_Id',
                '=',
                'a.id'
            )->leftJoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->select(
                'a.id AS ID_Actividad',
                'a.*',
                'ds.*',
                'p.*',
                'ha.*',
                'e.*',
                'em.*',
                'af.*',
                DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Asignadas) as Horas'),
                DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Reales) as HorasR')
            )->where(
                'a.ACT_Estado_Id', '=', 1
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->orderBy(
                'a.id', 'ASC'
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividadesProceso;
    }

    #Funcion para obtener las actividades en proceso del perfil de operación
    public static function obtenerActividadesProcesoPerfilHoy($id)
    {
        $actividadesProceso = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'PRY_Empresa_Id'
            )->leftjoin(
                'TBL_Horas_Actividad as ha',
                'ha.HRS_ACT_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->leftJoin(
                'TBL_Documentos_Soporte as ds',
                'ds.DOC_Actividad_Id',
                '=',
                'a.id'
            )->select(
                'a.id AS ID_Actividad',
                'a.*',
                'ds.*',
                'p.*',
                'ha.*',
                'e.*',
                'em.*',
                DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Asignadas) as Horas'),
                DB::raw('SUM(ha.HRS_ACT_Cantidad_Horas_Reales) as HorasR')
            )->where(
                'a.ACT_Estado_Id', '=', 1
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->where(
                'ha.HRS_ACT_Fecha_Actividad', '=', Carbon::now()->format('yy-m-d')
            )->orderBy(
                'a.id', 'ASC'
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividadesProceso;
    }

    #Función para obtener las actividades atrasadas del perfil de operación
    public static function obtenerActividadesAtrasadasPerfil($id)
    {
        $actividadesAtrasadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'PRY_Empresa_Id'
            )->leftjoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.id AS ID_Actividad',
                'a.*',
                'af.*',
                'p.*',
                'em.*',
                DB::raw('count(af.ACT_FIN_Actividad_Id) as fila')
            )->where(
                'a.ACT_Estado_Id', '=', 2
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->orderBy(
                'a.id'
            )->groupBy(
                'a.id'
            )->get();

        return $actividadesAtrasadas;
    }

    #Función para obtener las actividades finalizadas del perfil de operación
    public static function obtenerActividadesFinalizadasPerfil($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'PRY_Empresa_Id'
            )->join(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.id AS ID_Actividad',
                'a.*',
                'p.*',
                'af.*',
                'e.*',
                'em.*'
            )->where(
                'a.ACT_Estado_Id', '<>', 1
            )->where(
                'a.ACT_Estado_Id', '<>', 2
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->orderBy(
                'a.id'
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Función para obtener las actividades finalizadas del perfil de operación en los ultimos 8 días
    public static function obtenerActividadesFinalizadasPerfilOchoDias($id)
    {
        $actividadesFinalizadas = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Empresas as em',
                'em.id',
                '=',
                'PRY_Empresa_Id'
            )->join(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.id AS ID_Actividad',
                'a.*',
                'p.*',
                'af.*',
                'e.*',
                'em.*'
            )->where(
                'a.ACT_Estado_Id', '<>', 1
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->where(
                'af.ACT_FIN_Fecha_Finalizacion', '>=', Carbon::now()->subDays(8)->format('yy-m-d h:i:s')
            )->orderBy(
                'a.id'
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividadesFinalizadas;
    }

    #Función para obtener las actividades totales del proyecto seleccionado
    public static function obtenerTodasActividadesProyecto($id)
    {
        $actividades = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'r.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ur.USR_RLS_Usuario_Id',
                '=',
                'u.id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.*',
                'r.*',
                'p.*',
                'u.*',
                'e.*',
                'a.id as ID_Actividad'
            )->where(
                'p.id', '=', $id
            )->where(
                'ur.USR_RLS_Rol_Id', '!=', 3
            )->groupBy(
                'a.id'
            )->get();
        
        return $actividades;
    }

    #Función para obtener todas las actividades por proyecto del usuario autenticado
    public static function obtenerActividadesProyectoUsuario($id, $idUsuario)
    {
        $actividadesTotales = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Requerimientos as re',
                're.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                're.REQ_Proyecto_Id'
            )->join(
                'TBL_Usuarios as uu',
                'uu.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->where(
                'p.id', '=', $id
            )->where(
                'uu.id', '=', $idUsuario
            )->where(
                'a.ACT_Fecha_Fin_Actividad', '<=', Carbon::now()->format('y/m/d h:i:s')
            )->get();
        
        return $actividadesTotales;
    }

    #Función para obtener actividades entre rango de fechas
    public static function obtenerActividadesRango($fechaInicio, $fechaFin, $id)
    {
        #dd($fechaInicio->format('M d Y').' - '.$fechaFin->format('M d Y'));
        $actividades = DB::table('TBL_Actividades as a')
            ->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'a.ACT_Fecha_Inicio_Actividad', '>=', $fechaInicio
            )->where(
                'a.ACT_Fecha_Inicio_Actividad', '<=', $fechaFin
            )->get();
        
        return $actividades;
    }

    #Función para obtener las actividades generales por perfil de operación
    public static function obtenerGenerales($id)
    {
        $actividades = DB::table('TBL_Horas_Actividad as ha')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'ha.HRS_ACT_Actividad_Id'
            )->join(
                'TBL_Requerimientos as r',
                'r.id',
                '=',
                'a.ACT_Requerimiento_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.*',
                'r.*',
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Asignadas) as HorasE'),
                DB::raw('SUM(HRS_ACT_Cantidad_Horas_Reales) as HorasR'),
                'a.id as Id_Actividad'
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(function($q) {
                $q->where('e.id', 2)
                  ->orWhere('e.id', 1);
            })->groupBy(
                'a.id'
            )->get();
        
        return $actividades;
    }

    #Función para obtener las actividades generales por perfil de operación
    public static function obtenerTodasPerfilOperacion($id)
    {
        $actividades = Actividades::where(
            'ACT_Trabajador_Id', '=', $id
        )->get();
        
        return $actividades;
    }

    #Función para guardar la actividad en la Base de Datos
    public static function crearActividad($request, $idR, $idUsuario, $idEncargado){
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
            'ACT_Encargado_Id' => $idEncargado
        ]);

        LogCambios::guardar(
            'TBL_Empresas',
            'INSERT',
            'Creó la actividad con la siguiente información:'.
                ' ACT_Nombre_Actividad -> '.$request['ACT_Nombre_Actividad'].
                ', ACT_Descripcion_Actividad -> '.$request['ACT_Descripcion_Actividad'].
                ', ACT_Estado_Id -> 1'.
                ', ACT_Fecha_Inicio_Actividad -> '.$request['ACT_Fecha_Inicio_Actividad'].
                ', ACT_Fecha_Fin_Actividad -> '.$request['ACT_Fecha_Fin_Actividad'] . ' ' . $request['ACT_Hora_Entrega'].
                ', ACT_Costo_Estimado_Actividad -> 0'.
                ', ACT_Requerimiento_Id -> '.$idR.
                ', ACT_Trabajador_Id -> '.$idUsuario.
                ', ACT_Encargado_Id -> '.$idEncargado,
            session()->get('Usuario_Id')
        );
    }

    #Función para actualizar los datos de la Actividad
    public static function actualizarActividad($request, $idA, $idUsuario)
    {
        $oldActividad = Actividades::findOrFail($idA);
        $newActividad = Actividades::findOrFail($idA);
        $actividad = $newActividad->update([
            'ACT_Nombre_Actividad' => $request['ACT_Nombre_Actividad'],
            'ACT_Descripcion_Actividad' => $request['ACT_Descripcion_Actividad'],
            'ACT_Fecha_Inicio_Actividad' => $request['ACT_Fecha_Inicio_Actividad'],
            'ACT_Fecha_Fin_Actividad' => $request['ACT_Fecha_Fin_Actividad'].
                ' '.
                $request['ACT_Hora_Entrega'],
            'ACT_Costo_Estimado_Actividad' => 0,
            'ACT_Trabajador_Id' => $idUsuario,
        ]);

        LogCambios::guardar(
            'TBL_Empresas',
            'UPDATE',
            'Cambió los datos de la empresa:'.
                ' ACT_Nombre_Actividad -> '.$oldActividad->ACT_Nombre_Actividad.' / '.$request['ACT_Nombre_Actividad'].
                ', ACT_Descripcion_Actividad -> '.$oldActividad->ACT_Descripcion_Actividad.' / '.$request['ACT_Descripcion_Actividad'].
                ', ACT_Fecha_Inicio_Actividad -> '.$oldActividad->ACT_Fecha_Inicio_Actividad.' / '.$request['ACT_Fecha_Inicio_Actividad'].
                ', ACT_Fecha_Fin_Actividad -> '.$oldActividad->ACT_Fecha_Fin_Actividad.' / '.$request['ACT_Fecha_Fin_Actividad'] . ' ' . $request['ACT_Hora_Entrega'].
                ', ACT_Costo_Estimado_Actividad -> '.$oldActividad->ACT_Costo_Estimado_Actividad.' / 0'.
                ', ACT_Trabajador_Id -> '.$oldActividad->ACT_Trabajador_Id.' / '.$idUsuario,
            session()->get('Usuario_Id')
        );

        return $actividad;
    }

    #Funcion para actualizar el requerimiento de la actividad
    public static function actualizarRequerimientoActividad($idA, $request)
    {
        $oldReq = Actividades::findOrFail($idA);
        $newReq = Actividades::findOrFail($idA);
        
        $newReq->update(['ACT_Requerimiento_Id' => $request['ACT_Requerimiento']]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Cambió el requerimiento de la actividad '.$idA.':'.
                ' ACT_Requerimiento_Id -> '.$request['ACT_Requerimiento'],
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar la fechaa de finalización de la actividad
    public static function actualizarFechaFin($solicitud)
    {
        $oldFecha = Actividades::findOrFail($solicitud->Id_Actividad);
        $newFecha = Actividades::findOrFail($solicitud->Id_Actividad);
        $newFecha->update([
            'ACT_Estado_Id' => 1,
            'ACT_Fecha_Fin_Actividad' => Carbon::now()->addDays(1)->format('yy-m-d h:i:s')
        ]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Cambió el la fecha de entrrega de la actividad:'.
                ' ACT_Estado_Id -> '.$oldFecha->ACT_Estado_Id.' / '.$newFecha->ACT_Estado_Id.
                ', ACT_Fecha_Fin_Actividad -> '.$oldFecha->ACT_Fecha_Fin_Actividad.' / '.$newFecha->ACT_Fecha_Fin_Actividad,
            session()->get('Usuario_Id')
        );
    }

    #Funcion para cambiar estado de la actividad
    public static function actualizarEstadoActividad($id, $estado)
    {
        $oldActividad = Actividades::findOrFail($id);
        $newActividad = Actividades::findOrFail($id);
        
        $newActividad->update(['ACT_Estado_Id' => $estado]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Cambió el estado de la actividad '.$id.':'.
                ' ACT_Estado_Id -> '.$oldActividad->ACT_Estado_Id.' / '.$newActividad->ACT_Estado_Id,
            session()->get('Usuario_Id')
        );
    }

    #Funcion que actualiza el estado de la actividad a pagado y en que fecha se efectuó
    public static function actualizarEstadoPago($id, $estado, $fecha)
    {
        $oldEstado = Actividades::findOrFail($id);
        $estadoNew = Actividades::findOrFail($id);
        $estadoNew->update([
            'ACT_Estado_Id' => $estado,
            'ACT_Fecha_Pago' => $fecha
        ]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Cambió el estado del pago '.$id.':'.
                ' ACT_Estado_Id -> '.$oldEstado->ACT_Estado_Id.' / '.$estadoNew->ACT_Estado_Id.
                ', ACT_Fecha_Pago -> '.$oldEstado->ACT_Fecha_Pago.' / '.$estadoNew->ACT_Fecha_Pago,
            session()->get('Usuario_Id')
        );
    }

    #Función para actualizar el costo estimado de la actividad
    public static function actualizarCostoEstimado($idA, $horas, $costoHora)
    {
        $oldCosto = Actividades::findOrFail($idA);
        $newCosto = Actividades::findOrFail($idA);
        
        $newCosto->update([
            'ACT_Costo_Estimado_Actividad' =>($horas * $costoHora)
        ]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Actualizó el costo de la actividad:'.
                ' ACT_Costo_Estimado_Actividad -> '.$oldCosto->ACT_Costo_Estimado_Actividad.' / '.$newCosto->ACT_Costo_Estimado_Actividad,
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar el estado y costo real de la actividad
    public static function actualizarCostoReal($id, $estado, $costo)
    {
        $oldCosto = Actividades::findOrFail($id);
        $newCosto = Actividades::findOrFail($id);
        $newCosto->update([
            'ACT_Estado_Id' => $estado,
            'ACT_Costo_Real_Actividad' => $costo
        ]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Actualizó el costo de la actividad:'.
                ' ACT_Estado_Id -> '.$oldCosto->ACT_Estado_Id.' / '.$newCosto->ACT_Estado_Id.
                ' ACT_Costo_Real_Actividad -> '.$oldCosto->ACT_Costo_Real_Actividad.' / '.$newCosto->ACT_Costo_Real_Actividad,
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar la fechaa de finalización de la actividad
    public static function actualizarFechaFinEntrega($id)
    {
        $oldFecha = Actividades::findOrFail($id);
        $newFecha = Actividades::findOrFail($id);
        $newFecha->update([
            'ACT_Fecha_Fin_Actividad' => Carbon::now()->format('yy-m-d h:i:s')
        ]);

        LogCambios::guardar(
            'TBL_Actividades',
            'UPDATE',
            'Cambió el la fecha de entrrega de la actividad:'.
                ', ACT_Fecha_Fin_Actividad -> '.$oldFecha->ACT_Fecha_Fin_Actividad.' / '.$newFecha->ACT_Fecha_Fin_Actividad,
            session()->get('Usuario_Id')
        );
    }
}