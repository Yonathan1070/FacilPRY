<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Crear Roles Iniciales
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 1,
            'RLS_Nombre_Rol' => 'Administrador',
            'RLS_Descripcion_Rol' => 'Super Admin del sistema',
            'RLS_Empresa_Id' => 1
        ]);
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 2,
            'RLS_Nombre_Rol' => 'Director de Proyectos',
            'RLS_Descripcion_Rol' => 'Encargado del perfil de operación',
            'RLS_Empresa_Id' => 1
        ]);
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 3,
            'RLS_Nombre_Rol' => 'Finanzas',
            'RLS_Descripcion_Rol' => 'Pertence al area financiera de la compañía',
            'RLS_Empresa_Id' => 1
        ]);
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 4,
            'RLS_Nombre_Rol' => 'Tester',
            'RLS_Descripcion_Rol' => 'Encargado de realizar las pruebas a los Proyectos',
            'RLS_Empresa_Id' => 1
        ]);
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 5,
            'RLS_Nombre_Rol' => 'Cliente',
            'RLS_Descripcion_Rol' => 'Cliente que adquiere servicios de la compañía',
            'RLS_Empresa_Id' => 1
        ]);
        DB::table('TBL_Roles')->insert([
            'RLS_Rol_Id' => 6,
            'RLS_Nombre_Rol' => 'Perfil de Operación',
            'RLS_Descripcion_Rol' => 'Rol en el que se almacenarán los distintos roles creados en el sistema.',
            'RLS_Empresa_Id' => 1
        ]);
    }
}
