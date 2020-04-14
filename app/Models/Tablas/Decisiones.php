<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Decisiones, realiza las distintas consultas
 * que tenga que ver con la tabla Decisiones en la
 * Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class Decisiones extends Model
{
    protected $table = "TBL_Decisiones";
    protected $fillable = ['DCS_Nombre_Decision',
        'DCS_Descripcion_Decision',
        'DCS_Rango_Inicio_Decision',
        'DCS_Rango_Fin_Decision',
        'DSC_Indicador_Id',
        'DSC_Empresa_Id'];
    protected $guarded = ['id'];

    #Función para obtener la decisión por medio de la calificación obtenida
    public static function obtenerDecisionPorRango($calificacion)
    {
        $decision = Decisiones::where('DCS_Rango_Inicio_Decision', '<=', $calificacion)
            ->where(
                'DCS_Rango_Fin_Decision','>=', $calificacion
            )->first();
        
        return $decision;
    }

    public static function crearDecision($request, $decision)
    {
        Decisiones::create([
            'DCS_Nombre_Decision' => $request->DCS_Nombre_Decision,
            'DCS_Descripcion_Decision' => $request->DCS_Descripcion_Decision,
            'DCS_Rango_Inicio_Decision' => $request->DCS_Rango_Inicio_Decision,
            'DCS_Rango_Fin_Decision' => $request->DCS_Rango_Fin_Decision,
            'DSC_Indicador_Id' => $decision,
            'DSC_Empresa_Id' => $request->DSC_Empresa_Id
        ]);
    }

    public static function actualizarDecision($request, $decision)
    {
        Decisiones::create([
            'DCS_Nombre_Decision' => $request->DCS_Nombre_Decision,
            'DCS_Descripcion_Decision' => $request->DCS_Descripcion_Decision,
            'DCS_Rango_Inicio_Decision' => $request->DCS_Rango_Inicio_Decision,
            'DCS_Rango_Fin_Decision' => $request->DCS_Rango_Fin_Decision,
            'DSC_Indicador_Id' => $decision
        ]);
    }
}
