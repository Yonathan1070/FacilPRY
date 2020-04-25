<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->dateTime('NTF_Fecha');
            $table->text('NTF_Route')->nullable();
            $table->string('NTF_Parametro', 50)->nullable();
            $table->bigInteger('NTF_Valor_Parametro')->nullable();
            $table->boolean('NTF_Estado');
            $table->string('NTF_Icono', 40);
            $table->boolean('NTF_Visible')->default(1);
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
        Schema::dropIfExists('TBL_Notificaciones');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
