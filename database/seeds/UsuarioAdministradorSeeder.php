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


        //Agregar Empresa
        DB::table('TBL_Empresas')->insert([
            'EMP_Nombre_Empresa' => 'INK Agencia Digital',
            'EMP_NIT_Empresa' => '900681523-6',
            'EMP_Telefono_Empresa'  => '2889617',
            'EMP_Direccion_Empresa' => 'Cra 69 # 7-95',
            'EMP_Correo_Empresa' => 'inkdigital@gmail.com',
            'EMP_Logo_Empresa' => '1565140718.png',
        ]);
        //Agregar Usuarios
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula',
            'USR_Documento_Usuario' => '1070979976',
            'USR_Nombres_Usuario' => 'Yonathan',
            'USR_Apellidos_Usuario'  => 'Bohorquez',
            'USR_Fecha_Nacimiento_Usuario' => '1997/02/10',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3102144993',
            'USR_Correo_Usuario' => 'yonathancam@hotmail.com',
            'USR_Nombre_Usuario' => 'yonny',
            'password' => bcrypt('yonny'),
            'USR_Foto_Perfil_Usuario' => '1565046183.png',
            'USR_Supervisor_Id' => 0,
            'USR_Empresa_Id' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Administrador',
            'RLS_Descripcion_Rol' => 'Super Admin del sistema',
            'RLS_Empresa_Id' => 1
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 1,
            'USR_RLS_Usuario_Id' => 1,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula',
            'USR_Documento_Usuario' => '1070954014',
            'USR_Nombres_Usuario' => 'Edison',
            'USR_Apellidos_Usuario'  => 'Mendez',
            'USR_Fecha_Nacimiento_Usuario' => '1989/06/03',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3108666902',
            'USR_Correo_Usuario' => 'edialimenni@hotmail.com',
            'USR_Nombre_Usuario' => 'edison',
            'password' => bcrypt('edison'),
            'USR_Foto_Perfil_Usuario' => '1565094989.png',
            'USR_Supervisor_Id' => 1,
            'USR_Empresa_Id' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Director de Proyectos',
            'RLS_Descripcion_Rol' => 'Encargado del perfil de operación',
            'RLS_Empresa_Id' => 1
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 2,
            'USR_RLS_Usuario_Id' => 2,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula',
            'USR_Documento_Usuario' => '35526078',
            'USR_Nombres_Usuario' => 'Maria',
            'USR_Apellidos_Usuario'  => 'Rincon',
            'USR_Fecha_Nacimiento_Usuario' => '1997/02/10',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3104867316',
            'USR_Correo_Usuario' => 'rosamendez74@hotmail.com',
            'USR_Nombre_Usuario' => 'rosa',
            'password' => bcrypt('rosa'),
            'USR_Foto_Perfil_Usuario' => '1565106322.png',
            'USR_Supervisor_Id' => 2,
            'USR_Empresa_Id' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Finanzas',
            'RLS_Descripcion_Rol' => 'Pertence al area financiera de la compañía',
            'RLS_Empresa_Id' => 1
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 3,
            'USR_RLS_Usuario_Id' => 3,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula',
            'USR_Documento_Usuario' => '80393256',
            'USR_Nombres_Usuario' => 'Alirio',
            'USR_Apellidos_Usuario'  => 'Mendez',
            'USR_Fecha_Nacimiento_Usuario' => '1989/06/03',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3108799688',
            'USR_Correo_Usuario' => 'alirimendez@hotmail.com',
            'USR_Nombre_Usuario' => 'alirio',
            'password' => bcrypt('alirio'),
            'USR_Foto_Perfil_Usuario' => '1565129434.png',
            'USR_Supervisor_Id' => 2,
            'USR_Empresa_Id' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Tester',
            'RLS_Descripcion_Rol' => 'Encargado de realizar las pruebas a los Proyectos',
            'RLS_Empresa_Id' => 1
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 4,
            'USR_RLS_Usuario_Id' => 4,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula',
            'USR_Documento_Usuario' => '97021021982',
            'USR_Nombres_Usuario' => 'Camilo',
            'USR_Apellidos_Usuario'  => 'Rincon',
            'USR_Fecha_Nacimiento_Usuario' => '1989/06/06',
            'USR_Direccion_Residencia_Usuario' => 'Calle 5 # 13-18',
            'USR_Telefono_Usuario' => '3183684338',
            'USR_Correo_Usuario' => 'camyonathan@hotmail.com',
            'USR_Nombre_Usuario' => 'potrillo',
            'password' => bcrypt('potrillo'),
            'USR_Foto_Perfil_Usuario' => '1565143051.png',
            'USR_Supervisor_Id' => 2,
            'USR_Empresa_Id' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Cliente',
            'RLS_Descripcion_Rol' => 'Cliente que adquiere servicios de la compañía',
            'RLS_Empresa_Id' => 1
        ]);

        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 5,
            'USR_RLS_Usuario_Id' => 5,
            'USR_RLS_Estado' => 1
        ]);

        DB::table('TBL_Roles')->insert([
            'RLS_Nombre_Rol' => 'Perfil de Operación',
            'RLS_Descripcion_Rol' => 'Rol en el que se almacenarán los distintos roles creados en el sistema.',
            'RLS_Empresa_Id' => 1
        ]);
    }
}
