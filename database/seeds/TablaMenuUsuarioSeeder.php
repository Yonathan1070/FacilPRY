<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaMenuUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asignar Menú Administrador
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 1
        ]);
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 3
        ]);
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 9
        ]);
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 4
        ]);
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 2
        ]);
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 1,
            'MN_USR_Menu_Id' => 5
        ]);
        //Asignar Menú Financiero
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 2,
            'MN_USR_Menu_Id' => 16
        ]);
        //Asignar Menú Validador
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 3,
            'MN_USR_Menu_Id' => 13
        ]);
    }
}
