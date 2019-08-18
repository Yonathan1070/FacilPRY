<?php

namespace App\Models\Tablas;

use Illuminate\Database\Eloquent\Model;

class DocumentosSoporte extends Model
{
    protected $table = "TBL_Documentos_Soporte";
    protected $fillable = ['DOC_Actividad_Id',
        'ACT_Documento_Soporte_Actividad'];
    protected $guarded = ['id'];
}
