<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Agregar Usuario Administrador
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cédula de Ciudadanía',
            'USR_Documento_Usuario' => '80845861',
            'USR_Nombres_Usuario' => 'Alejandro',
            'USR_Apellidos_Usuario'  => 'Ayure',
            'USR_Fecha_Nacimiento_Usuario' => '1986/02/01',
            'USR_Direccion_Residencia_Usuario' => 'Calle 10A # 72C-32',
            'USR_Telefono_Usuario' => '3164637827',
            'USR_Correo_Usuario' => 'alejandro@inkdigital.co',
            'USR_Nombre_Usuario' => 'aayure',
            'password' => bcrypt('aayure'),
            'USR_Foto_Perfil_Usuario' => null,
            'USR_Supervisor_Id' => 0,
            'USR_Empresa_Id' => 1
        ]);
        #Agregar Usuario Financiero
        /*DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cédula de Ciudadanía',
            'USR_Documento_Usuario' => '35526078',
            'USR_Nombres_Usuario' => 'Maria',
            'USR_Apellidos_Usuario'  => 'Rincon',
            'USR_Fecha_Nacimiento_Usuario' => '1997/02/10',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3104867316',
            'USR_Correo_Usuario' => 'rosamendez74@hotmail.com',
            'USR_Nombre_Usuario' => 'rosa',
            'password' => bcrypt('rosa'),
            'USR_Foto_Perfil_Usuario' => null,
            'USR_Supervisor_Id' => 2,
            'USR_Empresa_Id' => 1
        ]);*/
        #Agregar Usuario Tester
        /*DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cédula de Ciudadanía',
            'USR_Documento_Usuario' => '80393256',
            'USR_Nombres_Usuario' => 'Alirio',
            'USR_Apellidos_Usuario'  => 'Mendez',
            'USR_Fecha_Nacimiento_Usuario' => '1989/06/03',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3108799688',
            'USR_Correo_Usuario' => 'alirimendez@hotmail.com',
            'USR_Nombre_Usuario' => 'alirio',
            'password' => bcrypt('alirio'),
            'USR_Foto_Perfil_Usuario' => null,
            'USR_Supervisor_Id' => 2,
            'USR_Empresa_Id' => 1
        ]);*/
    }
}
