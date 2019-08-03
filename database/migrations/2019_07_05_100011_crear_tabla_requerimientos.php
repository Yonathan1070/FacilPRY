<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaRequerimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Requerimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('REQ_Nombre_Requerimiento', 60);
            $table->text('REQ_Descripcion_Requerimiento');
            $table->unsignedBigInteger('REQ_Proyecto_Id');
            $table->foreign('REQ_Proyecto_Id', 'FK_Requerimientos_Proyectos')->references('id')->on('TBL_Proyectos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Requerimientos');
    }
}
