<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->unsignedBigInteger('PRY_Cliente_Id');
            $table->foreign('PRY_Cliente_Id', 'FK_Proyectos_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('PRY_Empresa_Id');
            $table->foreign('PRY_Empresa_Id', 'FK_Proyectos_Empresas')->references('id')->on('TBL_Empresas')->onDelete('restrict')->onUpdate('restrict');
            $table->boolean('PRY_Estado_Proyecto')->default(1);
            $table->boolean('PRY_Finalizado_Proyecto')->default(0);
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
        Schema::dropIfExists('TBL_Proyectos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
