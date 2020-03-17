<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Documentos Evidencia, realiza las distintas consultas que tenga
 * que ver con la tabla DocumentosEvidencias en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class DocumentosEvidencias extends Model
{
    protected $table = "TBL_Documentos_Evidencias";
    protected $fillable = ['DOC_Actividad_Finalizada_Id',
        'ACT_Documento_Evidencia_Actividad'];
    protected $guarded = ['id'];

    #Funcion para obtener los documentos evidencia de la actividad
    public static function obtenerDocumentosEvidencia($id)
    {
        $documentosEvidencia = DB::table('TBL_Documentos_Evidencias as d')
            ->join(
                'TBL_Actividades_Finalizadas as a',
                'a.id',
                '=',
                'd.DOC_Actividad_Finalizada_Id'
            )->where('a.id', '=', $id)
            ->get();
        
        return $documentosEvidencia;
    }

    #Funcion para crear los documentos de evidencia
    public static function crearDocumentosEvicendia($idActividad, $archivo)
    {
        DocumentosEvidencias::create([
            'DOC_Actividad_Finalizada_Id' => $idActividad,
            'ACT_Documento_Evidencia_Actividad' => $archivo
        ]);
    }

    #Funcion para obtener actividad a cobrar
    public static function obtenerDocumentosActividadCobrar($id)
    {
        $documentosEvidencias = DB::table('TBL_Actividades_Finalizadas as af')
            ->join('TBL_Actividades as a', 'a.id', '=', 'af.ACT_FIN_Actividad_Id')
            ->join(
                'TBL_Documentos_Evidencias as de',
                'de.DOC_Actividad_Finalizada_Id',
                '=',
                'af.id'
            )->where('a.id', '=', $id)
            ->get();

        return $documentosEvidencias;
    }
}
