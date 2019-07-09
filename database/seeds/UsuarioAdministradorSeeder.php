<?php

use Illuminate\Database\Seeder;

class UsuarioAdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '1070979976',
            'USR_Nombre' => 'Yonathan',
            'USR_Apellido'  => 'Bohorquez',
            'USR_Fecha_Nacimiento' => '1997/02/10',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '3102144993',
            'USR_Correo' => 'yonathancam@hotmail.com',
            'USR_Nombre_Usuario' => 'yonny',
            'password' => bcrypt('1070')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Administrador',
            'RLS_Descripcion' => 'Super Admin del sistema'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 1,
            'USR_RLS_Usuario_Id' => 1,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '1070954014',
            'USR_Nombre' => 'Edison',
            'USR_Apellido'  => 'Mendez',
            'USR_Fecha_Nacimiento' => '1989/06/03',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '3108666902',
            'USR_Correo' => 'edialimenni@hotmail.com',
            'USR_Nombre_Usuario' => 'edison',
            'password' => bcrypt('2668')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Director de Proyectos',
            'RLS_Descripcion' => 'Encargado del perfil de operación'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 2,
            'USR_RLS_Usuario_Id' => 2,
            'USR_RLS_Estado' => 1
        ]);
    }
}
