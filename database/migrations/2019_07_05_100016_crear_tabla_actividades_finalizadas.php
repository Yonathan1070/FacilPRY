<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->string('ACT_FIN_Titulo', 200);
            $table->text('ACT_FIN_Descripcion');
            $table->unsignedBigInteger('ACT_FIN_Actividad_Id');
            $table->foreign('ACT_FIN_Actividad_Id', 'FK_Actividades_Finalizadas_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('ACT_FIN_Fecha_Finalizacion');
            $table->text('ACT_FIN_Link')->nullable();
            $table->boolean('ACT_FIN_Revisado');
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
        Schema::dropIfExists('TBL_Actividades_Finalizadas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
