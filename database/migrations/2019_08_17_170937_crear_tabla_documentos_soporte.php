<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaDocumentosSoporte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Documentos_Soporte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('DOC_Actividad_Id');
            $table->foreign('DOC_Actividad_Id', 'FK_Documentos_Soporte_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->text('ACT_Documento_Soporte_Actividad')->nullable();
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
        Schema::dropIfExists('TBL_Documentos_Soporte');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
