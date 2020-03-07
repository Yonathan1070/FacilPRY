<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
}
