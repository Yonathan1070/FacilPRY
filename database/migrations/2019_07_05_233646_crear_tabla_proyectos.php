<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Proyectos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('PRY_Nombre_Proyecto', 100);
            $table->text('PRY_Descripcion_Proyecto');
            $table->double('PRY_Valor_Proyecto');
            $table->unsignedBigInteger('PRY_Cliente_Id');
            $table->foreign('PRY_Cliente_Id', 'FK_Proyectos_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Proyectos');
    }
}
