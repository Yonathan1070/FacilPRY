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

    //Funcion para crear las facturas de cobro
    public static function crearFactura($idA, $idC)
    {
        FacturasCobro::create([
            'FACT_Actividad_Id' => $idA,
            'FACT_Cliente_Id' => $idC,
            'FACT_Fecha_Cobro' => Carbon::now()
        ]);
    }

    //Funcion para obtener la información de la factura
    public static function obtenerDetalleFactura($id)
    {
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'r.*', 'fc.*')
            ->where('a.ACT_Costo_Real_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->where('p.id', '=', $id)
            ->get();

        return $informacion;
    }

    //Funcion para obtener el total de la factura
    public static function obtenerTotalFactura($id)
    {
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Real_Actividad) as Costo'))
            ->groupBy('r.REQ_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->first();

        return $total;
    }
}
