<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class DocumentosEvidencias extends Model
{
    protected $table = "TBL_Documentos_Evidencias";
    protected $fillable = ['DOC_Actividad_Finalizada_Id',
        'ACT_Documento_Evidencia_Actividad'];
    protected $guarded = ['id'];
}
