<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('EMP_Razon_Social_Empresa', 30);
            $table->string('EMP_Telefono_Empresa', 30);
            $table->string('EMP_Direccion_Empresa', 100);
            $table->string('EMP_Correo_Empresa', 100);
            $table->unsignedBigInteger('EMP_Usuario_Id');
            $table->foreign('EMP_Usuario_Id', 'FK_Empresas_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Empresas');
    }
}
