<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaFacturasCobro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Facturas_Cobro', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('FACT_Actividad_Id');
            $table->foreign('FACT_Actividad_Id', 'FK_Facturas_Cobro_Actividades')->references('id')->on('TBL_Actividades')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('FACT_Cliente_Id');
            $table->foreign('FACT_Cliente_Id', 'FK_Facturas_Cobro_Usuarios')->references('id')->on('TBL_Usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('FACT_Fecha_Cobro');
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
        Schema::dropIfExists('TBL_Facturas_Cobro');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
