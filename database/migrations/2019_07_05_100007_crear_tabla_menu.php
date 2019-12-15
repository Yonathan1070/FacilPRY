<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('MN_Menu_Id')->default(0);
            $table->string('MN_Nombre_Menu', 50);
            $table->string('MN_Nombre_Ruta_Menu', 100);
            $table->unsignedInteger('MN_Orden_Menu')->default(0);
            $table->string('MN_Icono_Menu', 50)->nullable();
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
        Schema::dropIfExists('TBL_Menu');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
