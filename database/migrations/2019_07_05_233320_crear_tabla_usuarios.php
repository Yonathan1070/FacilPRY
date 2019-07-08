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
        Schema::create('TBL_Usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('USR_Tipo_Documento', 30);
            $table->string('USR_Documento', 50)->unique();
            $table->string('USR_Nombre', 50);
            $table->string('USR_Apellido', 50);
            $table->date('USR_Fecha_Nacimiento');
            $table->string('USR_Direccion_Residencia', 100);
            $table->string('USR_Telefono', 20);
            $table->string('USR_Correo', 100);
            $table->string('USR_Nombre_Usuario', 15);
            $table->text('password');
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
        Schema::dropIfExists('TBL_Usuarios');
    }
}
