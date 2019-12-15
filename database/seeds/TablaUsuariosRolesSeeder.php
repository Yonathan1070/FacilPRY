<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaUsuariosRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asignar Rol Administrador
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 1,
            'USR_RLS_Usuario_Id' => 1,
            'USR_RLS_Estado' => 1
        ]);
        //Asignar Rol Finanzas
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 3,
            'USR_RLS_Usuario_Id' => 2,
            'USR_RLS_Estado' => 1
        ]);
        //Asignar Rol Validador
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 4,
            'USR_RLS_Usuario_Id' => 3,
            'USR_RLS_Estado' => 1
        ]);
    }
}
