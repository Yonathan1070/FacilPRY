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

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '35526078',
            'USR_Nombre' => 'Maria',
            'USR_Apellido'  => 'Rincon',
            'USR_Fecha_Nacimiento' => '1997/02/10',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '3104867316',
            'USR_Correo' => 'rosamendez74@hotmail.com',
            'USR_Nombre_Usuario' => 'rosa',
            'password' => bcrypt('1903')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Finanzas',
            'RLS_Descripcion' => 'Pertence al area financiera de la compañía'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 3,
            'USR_RLS_Usuario_Id' => 3,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '80393256',
            'USR_Nombre' => 'Alirio',
            'USR_Apellido'  => 'Mendez',
            'USR_Fecha_Nacimiento' => '1989/06/03',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '3108799688',
            'USR_Correo' => 'alirimendez@hotmail.com',
            'USR_Nombre_Usuario' => 'alirio',
            'password' => bcrypt('6826')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Tester',
            'RLS_Descripcion' => 'Encargado de realizar las pruebas a los Proyectos'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 4,
            'USR_RLS_Usuario_Id' => 4,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '97021021982',
            'USR_Nombre' => 'Camilo',
            'USR_Apellido'  => 'Rincon',
            'USR_Fecha_Nacimiento' => '1989/06/03',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '3183684338',
            'USR_Correo' => 'camyonathan@hotmail.com',
            'USR_Nombre_Usuario' => 'potrillo',
            'password' => bcrypt('1997')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Cliente',
            'RLS_Descripcion' => 'Cliente que adquiere servicios de la compañía'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 5,
            'USR_RLS_Usuario_Id' => 5,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento' => 'Cedula',
            'USR_Documento' => '123456',
            'USR_Nombre' => 'Qwerty',
            'USR_Apellido'  => 'Qwerty',
            'USR_Fecha_Nacimiento' => '1989/06/03',
            'USR_Direccion_Residencia' => 'Calle 5 # 13-18',
            'USR_Telefono' => '9876543210',
            'USR_Correo' => 'qerty@hotmail.com',
            'USR_Nombre_Usuario' => 'qwerty',
            'password' => bcrypt('qwerty')
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre' => 'Perfil de Operación',
            'RLS_Descripcion' => 'El distinto personal que labora en la compañía'
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 6,
            'USR_RLS_Usuario_Id' => 6,
            'USR_RLS_Estado' => 1
        ]);
    }
}
