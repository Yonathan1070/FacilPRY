<?php

use Illuminate\Database\Seeder;

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
            'TBL_Usuarios',
            'TBL_Roles',
            'TBL_Usuarios_Roles',
            'TBL_Indicadores',
            'TBL_Empresas',
            'TBL_Proyectos',
            'TBL_Requerimientos',
            'TBL_Actividades',
            'TBL_Calificaciones',
            'TBL_Decisiones',
            'TBL_Horas_Actividad'
        ]);
        $this->call(UsuarioAdministradorSeeder::class);
    }

    protected function truncateTablas(array $tablas){
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach($tablas as $tabla){
            DB::table($tabla)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
