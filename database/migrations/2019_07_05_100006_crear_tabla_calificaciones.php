<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaCalificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Calificaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('CALIF_calificacion');
            $table->unsignedBigInteger('CALIF_Trabajador_Id');
            $table->foreign('CALIF_Trabajador_Id', 'FK_Calificaciones_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('CALIF_Decision_Id');
            $table->foreign('CALIF_Decision_Id', 'FK_Calificaiones_Decisiones')->references('id')->on('TBL_Decisiones')->onDelete('restrict')->onUpdate('restrict');
            $table->date('CALIF_Fecha_Calificacion');
            /*$table->unsignedBigInteger('CALIF_Proyecto_Id');
            $table->foreign('CALIF_Proyecto_Id', 'FK_Calificaiones_Proyectos')->references('id')->on('TBL_Proyectos')->onDelete('restrict')->onUpdate('restrict');*/
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
        Schema::dropIfExists('TBL_Calificaciones');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
