<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CrearTablaFacturaAdicional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TBL_Factura_Adicional', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('FACT_AD_Descripcion');
            $table->double('FACT_AD_Precio_Factura');
            $table->bigInteger('FACT_AD_Estado_Id');
            $table->foreign('FACT_AD_Estado_Id', 'FK_Factura_Adicional_Estado')->references('id')->on('TBL_Estados')->onDelete('restrict')->onUpdate('restrict');
            $table->dateTime('FACT_AD_Fecha_Factura');
            $table->bigInteger('FACT_AD_Proyecto_Id');
            $table->foreign('FACT_AD_Proyecto_Id', 'FK_Factura_Adicional_Proyecto')->references('id')->on('TBL_Proyectos')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::dropIfExists('TBL_Factura_Adicional');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
