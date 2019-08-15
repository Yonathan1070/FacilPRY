<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaIndicadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Indicadores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('INDC_Nombre_Indicacor', 60);
            $table->text('INDC_Descripcion_Indicador');
            $table->unsignedBigInteger('INDC_Empresa_Id');
            $table->foreign('INDC_Empresa_Id', 'FK_Indicadores_Empresa')->references('id')->on('TBL_Empresas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Indicadores');
    }
}