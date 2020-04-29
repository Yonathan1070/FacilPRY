<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaLogCambios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Log_Cambios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('LOG_Tabla', 100);
            $table->string('LOG_Accion', 30);
            $table->text('LOG_Descripcion');
            $table->dateTime('LOG_Fecha');
            $table->unsignedBigInteger('LOG_Usuario');
            $table->foreign('LOG_Usuario', 'FK_Log_Cambios_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Log_Cambios');
    }
}
