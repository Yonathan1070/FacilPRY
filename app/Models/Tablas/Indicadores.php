<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Indicadores, donde se establecen los atributos de la tabla en la 
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
class Indicadores extends Model
{
    protected $table = "TBL_Indicadores";
    protected $fillable = ['INDC_Nombre_Indicador',
        'INDC_Descripcion_Indicador'
    ];
    protected $guarded = ['id'];
}
