<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('RLS_Nombre', 30)->unique();
            $table->text('RLS_Descripcion');
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
        Schema::dropIfExists('TBL_Roles');
    }
}
