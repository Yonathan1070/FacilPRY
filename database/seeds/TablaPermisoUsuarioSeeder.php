<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaPermisoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asignar Permiso Finanzas
        DB::table('TBL_Permiso_Usuario')->insert([
            'PRM_USR_Usuario_Id' => 2,
            'PRM_USR_Permiso_Id' => 15
        ]);
        //Asignar Permiso Validador
        DB::table('TBL_Permiso_Usuario')->insert([
            'PRM_USR_Usuario_Id' => 3,
            'PRM_USR_Permiso_Id' => 15
        ]);
    }
}
