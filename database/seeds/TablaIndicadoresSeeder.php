<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaIndicadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Agregar Indicadores
        DB::table('TBL_Indicadores')->insert([
            'INDC_Nombre_Indicador' => 'Eficiencia',
            'INDC_Descripcion_Indicador' => 'Capacidad para realizar o cumplir adecuadamente una función.',
        ]);
        DB::table('TBL_Indicadores')->insert([
            'INDC_Nombre_Indicador' => 'Eficacia',
            'INDC_Descripcion_Indicador' => 'Capacidad para conseguir el resultado que se busca.',
        ]);
        DB::table('TBL_Indicadores')->insert([
            'INDC_Nombre_Indicador' => 'Efectividad',
            'INDC_Descripcion_Indicador' => 'Capacidad para conseguir el resultado que se busca de forma adecuada. (Ser eficiente de una forma eficaz)',
        ]);
        DB::table('TBL_Indicadores')->insert([
            'INDC_Nombre_Indicador' => 'Productividad',
            'INDC_Descripcion_Indicador' => 'Algo',
        ]);
    }
}
