<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo Documentos Soporte, realiza las distintas consultas que tenga que 
 * ver con la tabla DocumentosSoporte en la Base de Datos.
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class DocumentosSoporte extends Model
{
    protected $table = "TBL_Documentos_Soporte";
    protected $fillable = ['DOC_Actividad_Id',
        'ACT_Documento_Soporte_Actividad'];
    protected $guarded = ['id'];

    #Función que obtiene los documentos soporte de la actividad
    public static function obtenerDocumentosSoporte($id)
    {
        $documentosSoporte = DB::table('TBL_Documentos_Soporte as d')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'd.DOC_Actividad_Id'
            )->where(
                'd.DOC_Actividad_Id', '=', $id
            )->get();
        
        return $documentosSoporte;
    }

    #Funcion para obtener el documento soporte de la actividad finalizada
    public static function obtenerDocumentoSoporteFinalizada($id)
    {
        $documentosSoporte = DB::table('TBL_Actividades_Finalizadas as af')
            ->join(
                'TBL_Actividades as a',
                'a.id',
                '=',
                'af.ACT_FIN_Actividad_Id'
            )->join(
                'TBL_Documentos_Soporte as ds',
                'ds.DOC_Actividad_Id',
                '=',
                'a.id'
            )->where(
                'af.id', '=', $id
            )->get();
        
        return $documentosSoporte;
    }

    #Funcion para obtener actividad a cobrar
    public static function obtenerDocumentosActividadCobrar($id)
    {
        $documentosSoporte = DB::table('TBL_Actividades as a')
            ->join(
                'TBL_Documentos_Soporte as ds',
                'ds.DOC_Actividad_Id',
                '=',
                'a.id'
            )->where(
                'a.id', '=', $id
            )->get();

        return $documentosSoporte;
    }

    #Funcion para obtener el documento soporte
    public static function obtenerDocumentoSoporte($id)
    {
        $documento = DB::table('TBL_Actividades as a')
            ->join('TBL_Documentos_Soporte as ds', 'ds.DOC_Actividad_Id', '=', 'a.id')
            ->select('ds.ACT_Documento_Soporte_Actividad')
            ->where('a.id', '=', $id)
            ->first();
        
        return $documento;
    }

    #Función que crea el documento soporte de la actividad
    public static function crearDocumentoSoporte($idA, $archivo)
    {
        DocumentosSoporte::create([
            'DOC_Actividad_Id' => $idA,
            'ACT_Documento_Soporte_Actividad' => $archivo
        ]);

        LogCambios::guardar(
            'TBL_Documentos_Soporte',
            'INSERT',
            'Cargó un documento de soporte:'.
                ' DOC_Actividad_Id -> '.$idA.
                ', ACT_Documento_Soporte_Actividad -> '.$archivo,
            session()->get('Usuario_Id')
        );
    }

    #Funcion para actualizar el documento soporte
    public static function actualizarDocumentoSoporte($documento, $archivo)
    {
        $documento->update([
            'ACT_Documento_Soporte_Actividad' => $archivo
        ]);

        LogCambios::guardar(
            'TBL_Documentos_Soporte',
            'INSERT',
            'Cargó un documento de soporte:'.
                ', ACT_Documento_Soporte_Actividad -> '.$archivo,
            session()->get('Usuario_Id')
        );
    }
}