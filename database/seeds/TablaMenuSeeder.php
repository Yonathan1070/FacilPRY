<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            'MN_Nombre_Menu' => 'Metricas',
            'MN_Nombre_Ruta_Menu' => 'inicio_director',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'pie_chart'
        ]);
        DB::table('TBL_Menu')->insert([
            'MN_Nombre_Menu' => 'Perfil de Operación',
            'MN_Nombre_Ruta_Menu' => 'perfil_operacion',
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
            'MN_Nombre_Ruta_Menu' => 'inicio_validador',
            'MN_Orden_Menu' => 1,
            'MN_Icono_Menu' => 'assignment_late'
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
            'MN_Nombre_Menu' => 'Finanzas',
            'MN_Nombre_Ruta_Menu' => 'inicio_finanzas',
            'MN_Orden_Menu' => 6,
            'MN_Icono_Menu' => 'playlist_add_check'
        ]);
    }
}
