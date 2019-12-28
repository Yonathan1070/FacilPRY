<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaEstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Agregar Estados
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'En Proceso'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Atrasado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Finalizado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Esperando Aprobación'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Aprobado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Rechazado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'En Facturación'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Facturado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Esperando Pago'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Pagado'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'En Cobro'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Esperando Aprobacion Cliente'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Solicitud de Cambio'
        ]);
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'Pago Pendiente'
        ]);
    }
}
