<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaPermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        DB::table('TBL_Permiso')->insert([
            'PRM_Nombre_Permiso' => 'Validador',
            'PRM_Slug_Permiso' => 'validador'
        ]);
    }
}
