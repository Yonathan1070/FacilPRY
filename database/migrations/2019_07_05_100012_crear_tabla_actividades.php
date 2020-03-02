<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->unsignedBigInteger('ACT_Estado_Id');
            $table->foreign('ACT_Estado_Id', 'FK_Actividades_Estados')->references('id')->on('TBL_Estados')->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('ACT_Fecha_Inicio_Actividad');
            $table->dateTime('ACT_Fecha_Fin_Actividad');
            $table->double('ACT_Costo_Estimado_Actividad');
            $table->double('ACT_Costo_Real_Actividad');
            $table->unsignedBigInteger('ACT_Requerimiento_Id');
            $table->foreign('ACT_Requerimiento_Id', 'FK_Actividades_Requerimientos')->references('id')->on('TBL_Requerimientos')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ACT_Trabajador_Id');
            $table->foreign('ACT_Trabajador_Id', 'FK_Actividades_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('ACT_Encargado_Id');
            $table->foreign('ACT_Encargado_Id', 'FK_Actividades_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Actividades');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
