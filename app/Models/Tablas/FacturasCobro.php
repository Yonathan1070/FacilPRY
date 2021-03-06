<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Facturas Cobro, realiza las distintas consultas que tenga que 
 * ver con la tabla Facturas Cobro en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class FacturasCobro extends Model
{
    protected $table = "TBL_Facturas_Cobro";
    protected $fillable = ['FACT_Actividad_Id',
        'FACT_Cliente_Id',
        'FACT_Fecha_Cobro'];
    protected $guarded = ['id'];

    #Funcion para obtener la información de la factura
    public static function obtenerDetalleFactura($id)
    {
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
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
            )->select(
                'p.*',
                'a.*',
                'u.*',
                'r.*',
                'fc.*'
            )->where(
                'a.ACT_Costo_Real_Actividad', '<>', 0
            )->where(
                'a.ACT_Estado_Id', '=', 9
            )->where(
                'p.id', '=', $id
            )->get();

        return $informacion;
    }

    #Funcion para obtener el total de la factura
    public static function obtenerTotalFactura($id)
    {
        $total = DB::table('TBL_Facturas_Cobro as fc')
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
            )->select(
                'a.*',
                DB::raw('SUM(a.ACT_Costo_Real_Actividad) as Costo')
            )->where(
                'p.id', '=', $id
            )->where(
                'a.ACT_Estado_Id', '=', 9
            )->groupBy(
                'r.REQ_Proyecto_Id'
            )->first();

        return $total;
    }

    #Funcion para obtener el total de la factura
    public static function obtenerProyectosFacturasPendientes()
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
                'a.ACT_Costo_Estimado_Actividad', '<>', 0
            )->where(
                'e.id', '=', 8
            )->orWhere(
                'e.id', '=', 9
            )->groupBy(
                'fc.FACT_Cliente_Id'
            )->get();

        return $proyectos;
    }

    #Funcion para crear las facturas de cobro
    public static function crearFactura($idA, $idC)
    {
        FacturasCobro::create([
            'FACT_Actividad_Id' => $idA,
            'FACT_Cliente_Id' => $idC,
            'FACT_Fecha_Cobro' => Carbon::now()
        ]);

        LogCambios::guardar(
            'TBL_Facturas_Cobro',
            'INSERT',
            'Agregó la respuesta a la actividad:'.
                ' FACT_Actividad_Id -> '.$idA.
                ', FACT_Cliente_Id -> '.$idC.
                ', FACT_Fecha_Cobro -> '.Carbon::now(),
            session()->get('Usuario_Id')
        );
    }
}
