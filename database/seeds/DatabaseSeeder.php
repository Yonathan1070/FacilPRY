<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTablas([
            'TBL_Empresas',
            'TBL_Usuarios',
            'TBL_Roles',
            'TBL_Usuarios_Roles',
            'TBL_Indicadores',
            'TBL_Decisiones',
            'TBL_Calificaciones',
            'TBL_Menu',
            'TBL_Menu_Usuario',
            'TBL_Proyectos',
            'TBL_Requerimientos',
            'TBL_Estados',
            'TBL_Actividades',
            'TBL_Facturas_Cobro',
            'TBL_Historial_Estados',
            'TBL_Horas_Actividad',
            'TBL_Actividades_Finalizadas',
            'TBL_Documentos_Soporte',
            'TBL_Documentos_Evidencias',
            'TBL_Notificaciones',
            'TBL_Permiso',
            'TBL_Permiso_Usuario',
            'TBL_Respuesta'
        ]);
        $this->call(TablaEmpresasSeeder::class);
        $this->call(TablaEstadosSeeder::class);
        $this->call(TablaIndicadoresSeeder::class);
        $this->call(TablaMenuSeeder::class);
        $this->call(TablaRolesSeeder::class);
        $this->call(TablaPermisoSeeder::class);
        $this->call(TablaUsuariosSeeder::class);
        $this->call(TablaMenuUsuarioSeeder::class);
        $this->call(TablaPermisoUsuarioSeeder::class);
        $this->call(TablaUsuariosRolesSeeder::class);
    }

    protected function truncateTablas(array $tablas){
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach($tablas as $tabla){
            DB::table($tabla)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
