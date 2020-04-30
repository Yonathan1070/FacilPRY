<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    #Funcion para obtener las decisiones registradas
    public static function obtenerDecisiones()
    {
        $decisiones = DB::table('TBL_Indicadores as i')
            ->join(
                'TBL_Decisiones as d',
                'd.DSC_Indicador_Id',
                '=',
                'i.id'
            )->get();
        
        return $decisiones;
    }

    #Funcion para obtener la diferencia de las decisiones registradas
    public static function obtenerDiferenciaDecisiones($id)
    {
        $diferencia = DB::table('TBL_Decisiones')
            ->select(
                DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia")
            )->where(
                'DSC_Indicador_Id', '=', $id
            )->groupBy(
                'id'
            )->get();
        
        return $diferencia;
    }

    #Funcion para obtener la diferencia de las decisiones registradas
    public static function obtenerDecisionById($id)
    {
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $id)
            ->select(
                'DCS_Rango_Inicio_Decision',
                'DCS_Rango_Fin_Decision',
                'DCS_Nombre_Decision'
            )->get();
        
        return $decisiones;
    }

    #Funcion para obtener la diferencia de las decisiones registradas
    public static function obtenerDiferenciaDecisionDistintasId($id, $idIndicador)
    {
        $decisiones = DB::table('TBL_Decisiones')
            ->select(
                DB::raw("DCS_Rango_Fin_Decision - DCS_Rango_Inicio_Decision as diferencia")
            )->where(
                'DSC_Indicador_Id', '=', $idIndicador
            )->where(
                'id', '<>', $id
            )->groupBy(
                'id'
            )->get();
        
        return $decisiones;
    }

    #Funcion para obtener la diferencia de las decisiones registradas
    public static function obtenerDecisionDistintasId($id, $idIndicador)
    {
        $decisiones = Decisiones::where('DSC_Indicador_Id', '=', $idIndicador)
            ->where(
                'id', '<>', $id
            )->select(
                'DCS_Rango_Inicio_Decision',
                'DCS_Rango_Fin_Decision',
                'DCS_Nombre_Decision'
            )->get();
        
        return $decisiones;
    }

    #Funcion para crear la decision
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

        LogCambios::guardar(
            'TBL_Decisiones',
            'INSERT',
            'Creó una decision de la siguiente forma:'.
                ' DCS_Nombre_Decision -> '.$request->DCS_Nombre_Decision.
                ', DCS_Descripcion_Decision -> '.$request->DCS_Descripcion_Decision.
                ', DCS_Rango_Inicio_Decision -> '.$request->DCS_Rango_Inicio_Decision.
                ', DCS_Rango_Fin_Decision -> '.$request->DCS_Rango_Fin_Decision.
                ', DSC_Indicador_Id -> '.$decision.
                ', DSC_Empresa_Id -> '.$request->DSC_Empresa_Id,
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar la decision
    public static function actualizarDecision($request, $decision)
    {
        Decisiones::create([
            'DCS_Nombre_Decision' => $request->DCS_Nombre_Decision,
            'DCS_Descripcion_Decision' => $request->DCS_Descripcion_Decision,
            'DCS_Rango_Inicio_Decision' => $request->DCS_Rango_Inicio_Decision,
            'DCS_Rango_Fin_Decision' => $request->DCS_Rango_Fin_Decision,
            'DSC_Indicador_Id' => $decision
        ]);

        LogCambios::guardar(
            'TBL_Decisiones',
            'INSERT',
            'Creó una decisión de la siguiente forma:'.
                ' DCS_Nombre_Decision -> '.$request->DCS_Nombre_Decision.
                ' DCS_Descripcion_Decision -> '.$request->DCS_Descripcion_Decision.
                ' DCS_Rango_Inicio_Decision -> '.$request->DCS_Rango_Inicio_Decision.
                ' DCS_Rango_Fin_Decision -> '.$request->DCS_Rango_Fin_Decision.
                ' DSC_Indicador_Id -> '.$decision,
            session()->get('Usuario_Id')
        );
    }
}
