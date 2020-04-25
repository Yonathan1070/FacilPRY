<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaSolicitudTiempo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Solicitud_Tiempo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('SOL_TMP_Actividad_Id');
            $table->foreign('SOL_TMP_Actividad_Id', 'FK_Solicitud_Tiempo_Actividad')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->integer('SOL_TMP_Hora_Solicitada')->default(1);
            $table->boolean('SOL_TMP_Estado_Solicitud')->default(0);
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
        Schema::dropIfExists('TBL_Solicitud_Tiempo');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
