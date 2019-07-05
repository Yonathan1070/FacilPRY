<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('DCS_Calificacion_Id');
            $table->foreign('DCS_Calificacion_Id', 'FK_Decisiones_Calificaciones')->references('id')->on('TBL_Calificaciones')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Decisiones');
    }
}
