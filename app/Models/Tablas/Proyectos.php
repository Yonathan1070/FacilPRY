<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Proyectos, donde se establecen los atributos de la tabla en la 
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
class Proyectos extends Model
{
    protected $table = "TBL_Proyectos";
    protected $fillable = ['PRY_Nombre_Proyecto',
        'PRY_Descripcion_Proyecto',
        'PRY_Cliente_Id',
        'PRY_Empresa_Id',
        'PRY_Estado_Proyecto',
        'PRY_Finalizado_Proyecto'
    ];
    protected $guarded = ['id'];

    public static function crearProyecto($request)
    {
        $proyecto = Proyectos::create($request->all());

        $result = DB::table('TBL_Proyectos as p')
            ->leftjoin(
                'TBL_Requerimientos as r',
                'r.REQ_Proyecto_Id',
                '=',
                'p.id'
            )->leftjoin(
                'TBL_Actividades as a',
                'a.ACT_Requerimiento_Id',
                '=',
                'r.id'
            )->leftJoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Empresas as e',
                'e.id',
                '=',
                'p.PRY_Empresa_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->select(
                'u.*',
                'p.*',
                'p.id as Proyecto_Id',
                DB::raw('COUNT(a.id) as Actividades_Totales'),
                DB::raw('COUNT(af.id) as Actividades_Finalizadas')
            )->where(
                'p.id', '=', $proyecto->id
            )->groupBy(
                'p.id'
            )->first();
        
        return $result;
    }

    #Función para obtener los proyectos no finalizados
    public static function obtenerNoFinalizados($id)
    {
        $proyectosNoFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin(
                'TBL_Requerimientos as r',
                'r.REQ_Proyecto_Id',
                '=',
                'p.id'
            )->leftjoin(
                'TBL_Actividades as a',
                'a.ACT_Requerimiento_Id',
                '=',
                'r.id'
            )->leftJoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Empresas as e',
                'e.id',
                '=',
                'p.PRY_Empresa_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )/*->join(
                'TBL_Usuarios as ut',
                'ut.id',
                '=',
                'a.ACT_Trabajador_Id'
            )->join(
                'TBL_Usuarios_Roles as ur',
                'ut.id',
                '=',
                'ur.USR_RLS_Usuario_Id'
            )->join(
                'TBL_Roles as ro',
                'ro.id',
                '=',
                'ur.USR_RLS_Rol_Id'
            )*/->select(
                'u.*',
                'p.*',
                'p.id as Proyecto_Id',
                DB::raw('COUNT(a.id) as Actividades_Totales'),
                DB::raw('COUNT(af.id) as Actividades_Finalizadas')
            )->where(
                'p.PRY_Empresa_Id', '=', $id
            )->where(
                'p.PRY_Finalizado_Proyecto', '=', 0
            )/*->where(
                'ro.id', '>', 3
            )*/->groupBy(
                'p.id'
            )->get();
        
        return $proyectosNoFinalizados;
    }

    #Función para obtener los proyectos finalizados
    public static function obtenerFinalizados($id)
    {
        $proyectosFinalizados = DB::table('TBL_Proyectos as p')
            ->leftjoin(
                'TBL_Requerimientos as r',
                'r.REQ_Proyecto_Id',
                '=',
                'p.id'
            )->leftjoin(
                'TBL_Actividades as a',
                'a.ACT_Requerimiento_Id',
                '=',
                'r.id'
            )->leftjoin(
                'TBL_Actividades_Finalizadas as af',
                'af.ACT_FIN_Actividad_Id',
                '=',
                'a.id'
            )->join(
                'TBL_Empresas as e',
                'e.id',
                '=',
                'p.PRY_Empresa_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->select(
                'u.*',
                'p.*',
                'p.id as Proyecto_Id',
                DB::raw('COUNT(a.id) as Actividades_Totales'),
                DB::raw('COUNT(af.id) as Actividades_Finalizadas')
            )->where(
                'p.PRY_Empresa_Id', '=', $id
            )->where(
                'p.PRY_Finalizado_Proyecto', '=', 1
            )->groupBy(
                'p.id'
            )->get();
        
        return $proyectosFinalizados;
    }

    #Funcion para obtener datos del proyecto
    public static function obtenerProyecto($id)
    {
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->where(
                'p.id', '=', $id
            )->first();
        
        return $proyecto;
    }

    #Función para obtener los proyectos asociados al usuario
    public static function obtenerProyectosAsociados($idCliente)
    {
        $proyectos = DB::table('TBL_Actividades as a')
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
            )->select(
                'u.*',
                'p.*'
            )->where(
                'u.id', '=', $idCliente
            )->get();
        
        return $proyectos;
    }

    #Funcion que obtiene los proyectos con facturas pendientes
    public static function obtenerProyectosConFacturas()
    {
        $proyectos = DB::table('TBL_Facturas_Cobro as fc')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'fc.FACT_Actividad_Id'
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
                'a.ACT_Estado_Id'
            )->select(
                'p.id as Id_Proyecto',
                'a.*',
                'p.*',
                'u.*',
                DB::raw('COUNT(a.id) as No_Actividades')
            )->where(
                'a.ACT_Costo_Real_Actividad', '<>', 0
            )->where(
                'a.ACT_Estado_Id', '=', 9
            )->groupBy(
                'fc.FACT_Cliente_Id'
            )->get();
        
        return $proyectos;
    }

    #Funcion que obtiene los proyectos con facturas adicionales pendientes
    public static function obtenerProyectosConFacturasAdicionales()
    {
        $proyectos = DB::table('TBL_Factura_Adicional as fa')
            ->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'fa.FACT_AD_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'fa.FACT_AD_Estado_Id'
            )->select(
                'p.id as Id_Proyecto',
                'fa.*',
                'p.*',
                'u.*',
                DB::raw('COUNT(fa.id) as No_Actividades')
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 9
            )->groupBy(
                'fa.FACT_AD_Proyecto_Id'
            )->get();
        
        return $proyectos;
    }

    #Funcion que obtiene los proyectos con facturas adicionales pendientes
    public static function obtenerProyectosConFacturasAdicionalesById($id)
    {
        $proyectos = DB::table('TBL_Factura_Adicional as fa')
            ->join(
                'TBL_Proyectos as p',
                'p.id',
                '=',
                'fa.FACT_AD_Proyecto_Id'
            )->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->join(
                'TBL_Estados as e',
                'e.id',
                '=',
                'fa.FACT_AD_Estado_Id'
            )->select(
                'p.id as Id_Proyecto',
                'fa.*',
                'p.*',
                'u.*',
                DB::raw('COUNT(fa.id) as No_Actividades')
            )->where(
                'u.id', '=', $id
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 9
            )->groupBy(
                'fa.FACT_AD_Proyecto_Id'
            )->get();
        
        return $proyectos;
    }

    #Función que obtiene los proyectos que tiene pendientes para realizar pago
    public static function obtenerProyectosPagar($idCliente)
    {
        $proyectos = DB::table('TBL_Actividades as a')
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
                'TBL_Empresas as e',
                'e.id',
                '=',
                'u.USR_Empresa_Id'
            )->join(
                'TBL_Estados as es',
                'es.id',
                '=',
                'a.ACT_Estado_Id'
            )->select(
                'a.*',
                'p.*',
                'a.id as Id_Actividad'
            )->where(
                'p.PRY_Cliente_Id', '=', $idCliente
            )->where(
                'es.id', '=', 9
            )->where(
                'a.ACT_Costo_Real_Actividad', '<>', 0
            )->groupBy(
                'p.id'
            )->get();
        
        return $proyectos;
    }

    #Función que obtiene los proyectos asociados al cliente autenticado
    public static function obtenerProyectosCliente($id)
    {
        $proyectos = DB::table('TBL_Proyectos as p')
            ->join(
                'TBL_Usuarios as u',
                'u.id',
                '=',
                'p.PRY_Cliente_Id'
            )->select(
                'p.*'
            )->where(
                'p.PRY_Cliente_Id', '=', $id
            )->get();
        
        return $proyectos;
    }

    #Función que obtiene los proyectos asociados a un perfil de operación
    public static function obtenerProyectosPerfil($id)
    {
        $proyectos = DB::table('TBL_Actividades as a')
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
            )->select(
                'p.*'
            )->where(
                'a.ACT_Trabajador_Id', '=', $id
            )->where(
                'p.PRY_Estado_Proyecto', '=', 1
            )->groupBy(
                'p.id'
            )->get();
        
        return $proyectos;
    }

    #Función que obtiene los proyectos asociados para obtener las métricas
    public static function obtenerProyectosMetricas($id)
    {
        $proyectos = DB::table('TBL_Actividades as a')
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
            )->select(
                'u.*',
                'p.*'
            )->where(
                'u.id', '=', $id
            )->groupBy(
                'p.id'
            )->get();
        
        return $proyectos;
    }

    #Función que cambia el estado del proyecto
    public static function cambiarEstado($id)
    {
        Proyectos::where('PRY_Empresa_Id', '=', $id)
            ->update([
                'PRY_Estado_Proyecto' => 0
            ]);
    }

    #Función que cambia el estado a activo el proyecto
    public static function cambiarEstadoActivado($id)
    {
        Proyectos::where('PRY_Empresa_Id', '=', $id)
            ->update([
                'PRY_Estado_Proyecto' => 1
            ]);
    }

    #Funcion que cambia el estado del proyecto a finalizado
    public static function finalizarProyecto($id)
    {
        Proyectos::where('id', '=', $id)
            ->update([
                'PRY_Finalizado_Proyecto' => 1
            ]);
    }

    #Funcion que reactiva el proyecto
    public static function activarProyecto($id)
    {
        Proyectos::where('id', '=', $id)
            ->update([
                'PRY_Finalizado_Proyecto' => 0
            ]);
    }
}
