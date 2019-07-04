<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_documento', 30);
            $table->string('documento', 50)->unique();
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->date('fehca_nacimiento');
            $table->string('direccion_residencia', 100);
            $table->string('telefono', 20);
            $table->string('correo', 100);
            $table->string('nombre_usuario', 15);
            $table->string('clave_usuario', 15);
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
        Schema::dropIfExists('usuarios');
    }
}
