<?php

namespace App\Models\Tablas;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Historial Estados, donde se establecen los atributos de la
 * tabla en la Base de Datos y se realizan las distintas operaciones
 * sobre la misma
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class HistorialEstados extends Model
{
    protected $table = "TBL_Historial_Estados";
    protected $fillable = ['HST_EST_Fecha',
        'HST_EST_Estado',
        'HST_EST_Actividad'];
    protected $guarded = ['id'];

    #Funcion para crear el historico de los estados de la actividad
    public static function crearHistorialEstado($idA, $estado){
        HistorialEstados::create([
            'HST_EST_Fecha' => Carbon::now(),
            'HST_EST_Estado' => $estado,
            'HST_EST_Actividad' => $idA
        ]);
    }
}
