<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Factura Adicional, realiza las distintas consultas que tenga que 
 * ver con la tabla Factura Adicional en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class FacturaAdicional extends Model
{
    protected $table = "TBL_Factura_Adicional";
    protected $fillable = [
        'FACT_AD_Descripcion',
        'FACT_AD_Precio_Factura',
        'FACT_AD_Estado_Id',
        'FACT_AD_Fecha_Factura',
        'FACT_AD_Proyecto_Id'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    #Funcion para obtener las tareas adicionales facturadas
    public static function obtenerFacturaAdicional($id)
    {
        $informacion = DB::table('TBL_Factura_Adicional as fa')
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
            )->select(
                'fa.*'
            )->where(
                'fa.FACT_AD_Precio_Factura', '<>', 0
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 9
            )->where(
                'u.id', '=', $id
            )->get();

        return $informacion;
    }

    #Funcion para obtener las tareas adicionales facturadas
    public static function obtenerFacturaAdicionalPendiente($id)
    {
        $informacion = DB::table('TBL_Factura_Adicional as fa')
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
            )->select(
                'fa.*'
            )->where(
                'fa.FACT_AD_Precio_Factura', '<>', 0
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 14
            )->where(
                'u.id', '=', $id
            )->get();

        return $informacion;
    }

    #Funcion para obtener la información de la factura
    public static function obtenerDetalleFacturaAdicional($id)
    {
        $informacion = DB::table('TBL_Factura_Adicional as fa')
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
            )->select(
                'p.id as Id_Proyecto',
                'p.*',
                'fa.*',
                'u.*'
            )->where(
                'fa.FACT_AD_Precio_Factura', '<>', 0
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 9
            )->where(
                'p.id', '=', $id
            )->get();

        return $informacion;
    }

    #Funcion para obtener el total de la factura
    public static function obtenerTotalFacturaAdicional($id)
    {
        $total = DB::table('TBL_Factura_Adicional as fa')
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
            )->select(
                'fa.*',
                DB::raw('SUM(fa.FACT_AD_Precio_Factura) as Costo')
            )->where(
                'p.id', '=', $id
            )->where(
                'fa.FACT_AD_Estado_Id', '=', 9
            )->groupBy(
                'p.id'
            )->first();

        return $total;
    }

    #Funcion para crear las facturas de cobro
    public static function crearFactura($request)
    {
        FacturaAdicional::create([
            'FACT_AD_Descripcion' => $request['FACT_AD_Descripcion'],
            'FACT_AD_Precio_Factura' => $request['FACT_AD_Costo'],
            'FACT_AD_Estado_Id' => 9,
            'FACT_AD_Fecha_Factura' => Carbon::now(),
            'FACT_AD_Proyecto_Id' => $request['ProyectoSelect']
        ]);

        LogCambios::guardar(
            'TBL_Factura_Adicional',
            'UPDATE',
            'Creó una factura adicional de la siguiente forma:'.
                ' FACT_AD_Descripcion -> '.$request['FACT_AD_Descripcion'].
                ', FACT_AD_Precio_Factura -> '.$request['FACT_AD_Costo'].
                ', FACT_AD_Estado_Id -> 9'.
                ', FACT_AD_Fecha_Factura -> '.Carbon::now().
                ', FACT_AD_Proyecto_Id -> '.$request['ProyectoSelect'],
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar el estado de la factura de cobro
    public static function actualizarFactura($id, $estado)
    {
        $oldEstado = FacturaAdicional::findOrFail($id);
        $estadoNew = FacturaAdicional::findOrFail($id);
        
        $estadoNew->update([
            'FACT_AD_Estado_Id' => $estado,
        ]);
        
        LogCambios::guardar(
            'TBL_Factura_Adicional',
            'UPDATE',
            'Cambió el estado del pago en la factura adicional '.$id.':'.
                ' FACT_AD_Estado_Id -> '.$oldEstado->FACT_AD_Estado_Id.' / '.$estadoNew->FACT_AD_Estado_Id,
            session()->get('Usuario_Id')
        );
    }
}
