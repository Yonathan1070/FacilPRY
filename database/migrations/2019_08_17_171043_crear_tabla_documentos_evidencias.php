<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaDocumentosEvidencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Documentos_Evidencias', function (Blueprint $table) {
            $table->unsignedBigInteger('DOC_Actividad_Id');
            $table->foreign('DOC_Actividad_Id', 'FK_Documentos_Evidencias_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->text('ACT_Documento_Evidencia_Actividad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TBL_Documentos_Evidencias');
    }
}
