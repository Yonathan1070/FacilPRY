<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaMenuRol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Menu_Rol', function (Blueprint $table) {
            $table->unsignedBigInteger('MN_RL_Rol_Id');
            $table->foreign('MN_RL_Rol_Id', 'FK_Menu_Rol_Roles')->references('id')->on('TBL_Roles')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('MN_RL_Menu_Id');
            $table->foreign('MN_RL_Menu_Id', 'FK_Menu_Rol_Menu')->references('id')->on('TBL_Menu')->onDelete('restrict')->onUpdate('restrict');
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
