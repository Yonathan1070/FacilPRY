<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaNotificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Notificaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('NTF_Titulo', 100);
            $table->unsignedBigInteger('NTF_De');
            $table->foreign('NTF_De', 'FK_Notificaciones_Usuarios_De')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('NTF_Para');
            $table->foreign('NTF_Para', 'FK_Notificaciones_Usuarios_Para')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->date('NTF_Fecha');
            $table->text('NTF_Route');
            $table->boolean('NTF_Estado');
            $table->string('NTF_Icono', 40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TBL_Notificaciones');
    }
}
