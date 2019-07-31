<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaActividadesFinalizadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Actividades_Finalizadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('ACT_FIN_Descripcion');
            $table->text('ACT_FIN_Documento_Soporte');
            $table->unsignedBigInteger('ACT_FIN_Actividad_Id');
            $table->foreign('ACT_FIN_Actividad_Id', 'FK_Actividades_Finalizadas_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->string('ACT_FIN_Estado', 30);
            $table->dateTime('ACT_FIN_Fecha_Finalizacion');
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
        Schema::dropIfExists('TBL_ActividadesFinalizadas');
    }
}
