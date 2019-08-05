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
            $table->string('EMP_NIT_Empresa', 30)->nullable();
            $table->string('EMP_Telefono_Empresa', 30)->nullable();
            $table->string('EMP_Direccion_Empresa', 100)->nullable();
            $table->string('EMP_Correo_Empresa', 100);
            $table->text('EMP_Logo_Empresa')->nullable();
            $table->boolean('EMP_Estado_Empresa');
            $table->date('EMP_Fecha_Suscribcion_Empresa')->nullable();
            $table->date('EMP_Prueba_Empresa')->nullable();
            $table->date('EMP_Fecha_Pago_Empresa')->nullable();
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
