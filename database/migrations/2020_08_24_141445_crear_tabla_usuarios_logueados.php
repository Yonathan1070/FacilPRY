<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CrearTablaUsuariosLogueados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Sesion_Usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('SES_USR_Fecha_Sesion');
            $table->boolean('SES_USR_Estado_Sesion');
            $table->unsignedBigInteger('SES_USR_Usuario_Id');
            $table->foreign('SES_USR_Usuario_Id', 'FK_Sesion_Usuario_Usuario')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Sesion_Usuario');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
