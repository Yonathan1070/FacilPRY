<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('RLS_Rol_Id')->default(0);
            $table->string('RLS_Nombre_Rol', 30);
            $table->text('RLS_Descripcion_Rol');
            $table->unsignedBigInteger('RLS_Empresa_Id');
            $table->foreign('RLS_Empresa_Id', 'FK_Roles_Empresa')->references('id')->on('TBL_Empresas')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
