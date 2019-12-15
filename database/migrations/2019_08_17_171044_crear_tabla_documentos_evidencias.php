<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('DOC_Actividad_Finalizada_Id');
            $table->foreign('DOC_Actividad_Finalizada_Id', 'FK_Documentos_Evidencias_Actividades_Finalizadas')->references('id')->on('TBL_Actividades_Finalizadas')->onDelete('restrict')->onUpdate('restrict');
            $table->text('ACT_Documento_Evidencia_Actividad')->nullable();
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_spanish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('TBL_Documentos_Evidencias');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
