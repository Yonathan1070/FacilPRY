<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('TBL_Estados')->insert([
            'EST_Nombre_Estado' => 'En Cobro'
        ]);

        //Agregar Empresa
        DB::table('TBL_Empresas')->insert([
            'EMP_Nombre_Empresa' => 'INK Agencia Digital',
            'EMP_NIT_Empresa' => '900681523-6',
            'EMP_Telefono_Empresa'  => '2889617',
            'EMP_Direccion_Empresa' => 'Cra 69 # 7-95',
            'EMP_Correo_Empresa' => 'gestion@inkdigital.co',
            'EMP_Logo_Empresa' => '1574183841.png',
        ]);

        //Agregar Menús Iniciales
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Inicio Administrador',
            'MN_Nombre_Ruta_Menu' => 'inicio_administrador',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'home'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Menu',
            'MN_Nombre_Ruta_Menu' => 'menu',
            'MN_Orden_Menu' => 5,
            'MN_Icono_Menu' => 'list'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Director de Proyectos',
            'MN_Nombre_Ruta_Menu' => 'directores_administrador',
            'MN_Orden_Menu' => 2,
            'MN_Icono_Menu' => 'insert_chart'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Roles',
            'MN_Nombre_Ruta_Menu' => 'roles',
            'MN_Orden_Menu' => 4,
            'MN_Icono_Menu' => 'accessibility'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Permisos',
            'MN_Nombre_Ruta_Menu' => 'asignar_rol_administrador',
            'MN_Orden_Menu' => 6,
            'MN_Icono_Menu' => 'assignment_ind'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Inicio Director',
            'MN_Nombre_Ruta_Menu' => 'inicio_director',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'home'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Perfil de Operación',
            'MN_Nombre_Ruta_Menu' => 'perfil_operacion_director',
            'MN_Orden_Menu' => 2,
            'MN_Icono_Menu' => 'account_circle'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Empresas',
            'MN_Nombre_Ruta_Menu' => 'empresas',
            'MN_Orden_Menu' => 3,
            'MN_Icono_Menu' => 'business'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Decisiones',
            'MN_Nombre_Ruta_Menu' => 'decisiones',
            'MN_Orden_Menu' => 3,
            'MN_Icono_Menu' => 'record_voice_over'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Cobros',
            'MN_Nombre_Ruta_Menu' => 'cobros',
            'MN_Orden_Menu' => 5,
            'MN_Icono_Menu' => 'attach_money'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Inicio Perfil Operación',
            'MN_Nombre_Ruta_Menu' => 'inicio_perfil_operacion',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'home'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Actividades',
            'MN_Nombre_Ruta_Menu' => 'actividades_perfil_operacion',
            'MN_Orden_Menu' => 2,
            'MN_Icono_Menu' => 'toc'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Validador',
            'MN_Nombre_Ruta_Menu' => 'inicio_tester',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'home'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Inicio Cliente',
            'MN_Nombre_Ruta_Menu' => 'inicio_cliente',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'home'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Actividades',
            'MN_Nombre_Ruta_Menu' => 'actividades_cliente',
            'MN_Orden_Menu' => 2,
            'MN_Icono_Menu' => 'toc'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Inicio Finanzas',
            'MN_Nombre_Ruta_Menu' => 'inicio_finanzas',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'playlist_add_check'
        ]);

        //Agregar Permisos Iniciales
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Actividades',
            'PRM_Slug_Permiso' => 'listar-actividades'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Actividades',
            'PRM_Slug_Permiso' => 'crear-actividades'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Actividades Cliente',
            'PRM_Slug_Permiso' => 'crear-actividades-cliente'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Actividades',
            'PRM_Slug_Permiso' => 'editar-actividades'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Actividades',
            'PRM_Slug_Permiso' => 'eliminar-actividades'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Clientes',
            'PRM_Slug_Permiso' => 'listar-clientes'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Clientes',
            'PRM_Slug_Permiso' => 'crear-clientes'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Clientes',
            'PRM_Slug_Permiso' => 'editar-clientes'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Clientes',
            'PRM_Slug_Permiso' => 'eliminar-clientes'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Cobros',
            'PRM_Slug_Permiso' => 'listar-cobros'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Decisiones',
            'PRM_Slug_Permiso' => 'listar-decisiones'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Decisiones',
            'PRM_Slug_Permiso' => 'crear-decisiones'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Decisiones',
            'PRM_Slug_Permiso' => 'editar-decisiones'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Decisiones',
            'PRM_Slug_Permiso' => 'eliminar-decisiones'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Perfil',
            'PRM_Slug_Permiso' => 'editar-perfil'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Proyectos',
            'PRM_Slug_Permiso' => 'listar-proyectos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Proyectos',
            'PRM_Slug_Permiso' => 'crear-proyectos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Requerimientos',
            'PRM_Slug_Permiso' => 'listar-requerimientos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Requerimientos',
            'PRM_Slug_Permiso' => 'crear-requerimientos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Requerimientos',
            'PRM_Slug_Permiso' => 'editar-requerimientos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Requerimientos',
            'PRM_Slug_Permiso' => 'eliminar-requerimientos'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Roles',
            'PRM_Slug_Permiso' => 'listar-roles'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Roles',
            'PRM_Slug_Permiso' => 'crear-roles'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Roles',
            'PRM_Slug_Permiso' => 'editar-roles'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Roles',
            'PRM_Slug_Permiso' => 'eliminar-roles'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Listar Empresas',
            'PRM_Slug_Permiso' => 'listar-empresas'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Crear Empresas',
            'PRM_Slug_Permiso' => 'crear-empresas'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Editar Empresas',
            'PRM_Slug_Permiso' => 'editar-empresas'
        ]);
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Eliminar Empresas',
            'PRM_Slug_Permiso' => 'eliminar-empresas'
        ]);

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


        //Agregar Usuario Administrador
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula Ciudadanía',
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
        //Asignar Rol
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 1,
            'USR_RLS_Usuario_Id' => 1,
            'USR_RLS_Estado' => 1
        ]);
        //Asignar Menú
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

        //Agregar Usuario Financiero
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula Ciudadanía',
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
        ]);
        //Asignar Rol
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 3,
            'USR_RLS_Usuario_Id' => 2,
            'USR_RLS_Estado' => 1
        ]);
        //Asignar Menú
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 2,
            'MN_USR_Menu_Id' => 16
        ]);
        //Asignar Permiso
        DB::table('TBL_Permiso_Usuario')->insert([
            'PRM_USR_Usuario_Id' => 2,
            'PRM_USR_Permiso_Id' => 15
        ]);

        //Agregar Usuario Tester
        DB::table('TBL_Usuarios')->insert([
            'USR_Tipo_Documento_Usuario' => 'Cedula Ciudadanía',
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
        ]);
        //Asignar Rol
        DB::table('TBL_Usuarios_Roles')->insert([
            'USR_RLS_Rol_Id' => 4,
            'USR_RLS_Usuario_Id' => 3,
            'USR_RLS_Estado' => 1
        ]);
        //Asignar Menú
        DB::table('TBL_Menu_Usuario')->insert([
            'MN_USR_Usuario_Id' => 3,
            'MN_USR_Menu_Id' => 13
        ]);
        //Asignar Permiso
        DB::table('TBL_Permiso_Usuario')->insert([
            'PRM_USR_Usuario_Id' => 3,
            'PRM_USR_Permiso_Id' => 15
        ]);
    }
}
