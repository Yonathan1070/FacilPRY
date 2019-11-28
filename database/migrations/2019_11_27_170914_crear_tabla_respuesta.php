<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaRespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Respuesta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('RTA_Titulo', 200)->nullable();
            $table->text('RTA_Respuesta')->nullable();
            $table->unsignedBigInteger('RTA_Actividad_Finalizada_Id');
            $table->foreign('RTA_Actividad_Finalizada_Id', 'FK_Respuesta_Actividades_Finalizadas')->references('id')->on('TBL_Actividades_Finalizadas')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('RTA_Estado_Id');
            $table->foreign('RTA_Estado_Id', 'FK_Respuesta_Estados')->references('id')->on('TBL_Estados')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('RTA_Usuario_Id')->default(0);
            $table->dateTime('RTA_Fecha_Respuesta')->nullable();
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
        Schema::dropIfExists('TBL_Respuesta');
    }
}
