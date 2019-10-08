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
            $table->string('USR_Tipo_Documento_Usuario', 30);
            $table->string('USR_Documento_Usuario', 50)->unique();
            $table->string('USR_Nombres_Usuario', 50);
            $table->string('USR_Apellidos_Usuario', 50);
            $table->date('USR_Fecha_Nacimiento_Usuario');
            $table->string('USR_Direccion_Residencia_Usuario', 100);
            $table->string('USR_Telefono_Usuario', 20);
            $table->string('USR_Correo_Usuario', 100);
            $table->string('USR_Nombre_Usuario', 15);
            $table->text('password');
            $table->text('USR_Foto_Perfil_Usuario')->nullable();
            $table->unsignedBigInteger('USR_Supervisor_Id')->default(0);
            $table->unsignedBigInteger('USR_Empresa_Id');
            $table->foreign('USR_Empresa_Id', 'FK_Usuarios_Empresa')->references('id')->on('TBL_Empresas')->onDelete('restrict')->onUpdate('restrict');
            $table->bigInteger('USR_Costo_Hora')->default(0);
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
