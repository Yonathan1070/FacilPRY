<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaActividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Actividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ACT_Nombre_Actividad', 60);
            $table->text('ACT_Descripcion_Actividad');
            $table->text('ACT_Documento_Soporte_Actividad')->nullable();
            $table->boolean('ACT_Estado_Actividad');
            $table->unsignedBigInteger('ACT_Proyecto_Id');
            $table->foreign('ACT_Proyecto_Id', 'FK_Actividades_Proyectos')->references('id')->on('TBL_Proyectos')->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('ACT_Fecha_Inicio_Actividad');
            $table->dateTime('ACT_Fecha_Fin_Actividad');
            $table->double('ACT_Costo_Actividad');
            $table->unsignedBigInteger('ACT_Usuario_Id');
            $table->foreign('ACT_Usuario_Id', 'FK_Actividades_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ACT_Requerimiento_Id');
            $table->foreign('ACT_Requerimiento_Id', 'FK_Actividades_Requerimientos')->references('id')->on('TBL_Requerimientos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Actividades');
    }
}
