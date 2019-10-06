<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaMenuUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Menu_Usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('MN_USR_Rol_Id');
            $table->foreign('MN_USR_Usuario_Id', 'FK_Menu_Usuario_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('MN_USR_Menu_Id');
            $table->foreign('MN_USR_Menu_Id', 'FK_Menu_Usuario_Menu')->references('id')->on('TBL_Menu')->onDelete('cascade')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Menu_Rol');
    }
}
