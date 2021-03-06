<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaHorasActividades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Horas_Actividad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('HRS_ACT_Actividad_Id');
            $table->foreign('HRS_ACT_Actividad_Id', 'FK_Horas_Actividad_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->date('HRS_ACT_Fecha_Actividad');
            $table->unsignedBigInteger('HRS_ACT_Cantidad_Horas_Asignadas');
            $table->unsignedBigInteger('HRS_ACT_Cantidad_Horas_Reales')->nullable();
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
        Schema::dropIfExists('TBL_Horas_Actividad');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
