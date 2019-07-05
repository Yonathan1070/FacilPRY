<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCalificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Calificaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('CALIF_calificacion');
            $table->unsignedBigInteger('CALIF_Indicador_Id');
            $table->foreign('CALIF_Indicador_Id', 'FK_Calificaciones_Indicadores')->references('id')->on('TBL_Indicadores')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('CALIF_Usuario_Id');
            $table->foreign('CALIF_Usuario_Id', 'FK_Calificaciones_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('CALIF_Proyecto_Id');
            $table->foreign('CALIF_Proyecto_Id', 'FK_Calificaiones_Proyectos')->references('id')->on('TBL_Proyectos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Calificaciones');
    }
}
