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

    //Función que obtiene los documentos soporte de la actividad
    public static function obtenerDocumentosSoporte($id)
    {
        $documentosSoporte = DB::table('TBL_Documentos_Soporte as d')
            ->join('TBL_Actividades as a', 'a.id', '=', 'd.DOC_Actividad_Id')
            ->where('d.DOC_Actividad_Id', '=', $id)
            ->get();
        
        return $documentosSoporte;
    }

    //Función que crea el documento soporte de la actividad
    public static function crearDocumentoSoporte($idA, $archivo)
    {
        DocumentosSoporte::create([
            'DOC_Actividad_Id' => $idA,
            'ACT_Documento_Soporte_Actividad' => $archivo
        ]);
    }

    //Funcion para actualizar el documento soporte
    public static function actualizarDocumentoSoporte($documento, $archivo)
    {
        $documento->update([
            'ACT_Documento_Soporte_Actividad' => $archivo
        ]);
    }
}