<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('EMP_Nombre_Empresa', 100);
            $table->string('EMP_NIT_Empresa', 30)->nullable();
            $table->string('EMP_Telefono_Empresa', 30)->nullable();
            $table->string('EMP_Direccion_Empresa', 100)->nullable();
            $table->string('EMP_Correo_Empresa', 100);
            $table->text('EMP_Logo_Empresa')->nullable();
            $table->bigInteger('EMP_Empresa_Id')->default(0);
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
        Schema::dropIfExists('TBL_Empresas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
