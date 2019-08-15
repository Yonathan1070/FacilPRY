<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaHistorialEstados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Historial_Estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('HST_EST_Fecha');
            $table->unsignedBigInteger('HST_EST_Estado');
            $table->foreign('HST_EST_Estado', 'FK_Historial_Estados_Estado')->references('id')->on('TBL_Estados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('HST_EST_Actividad');
            $table->foreign('HST_EST_Actividad', 'FK_Historial_Estados_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Historial_Estados');
    }
}
