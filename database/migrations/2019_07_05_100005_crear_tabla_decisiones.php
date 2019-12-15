<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaDecisiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Decisiones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('DCS_Nombre_Decision', 60);
            $table->text('DCS_Descripcion_Decision');
            $table->integer('DCS_Rango_Inicio_Decision');
            $table->integer('DCS_Rango_Fin_Decision');
            $table->unsignedBigInteger('DSC_Indicador_Id');
            $table->foreign('DSC_Indicador_Id', 'FK_Decisiones_Indicadores')->references('id')->on('TBL_Indicadores')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('DSC_Empresa_Id');
            $table->foreign('DSC_Empresa_Id', 'FK_Decisiones_Empresas')->references('id')->on('TBL_Empresas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Decisiones');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
