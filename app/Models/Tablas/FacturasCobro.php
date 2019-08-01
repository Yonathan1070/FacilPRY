<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class FacturasCobro extends Model
{
    protected $table = "TBL_Facturas_Cobro";
    protected $fillable = ['FACT_Actividad_Id',
        'FACT_Cliente_Id',
        'FACT_Fecha_Cobro'];
    protected $guarded = ['id'];
}
