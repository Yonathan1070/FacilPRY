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
            $table->dateTime('FACT_AD_Fecha_Factura');
            $table->bigInteger('FACT_AD_Proyecto_Id');
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
