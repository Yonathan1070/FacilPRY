<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

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
