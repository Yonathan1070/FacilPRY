<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaUsuariosRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Usuarios_Roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('USR_RLS_Rol_Id');
            $table->foreign('USR_RLS_Rol_Id', 'FK_Usuarios_Roles_Roles')->references('id')->on('TBL_Roles')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('USR_RLS_Usuario_Id');
            $table->foreign('USR_RLS_Usuario_Id', 'FK_Usuarios_Roles_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->boolean('USR_RLS_Estado');
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
        Schema::dropIfExists('TBL_Usuarios_Roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
