<?php

use Illuminate\Database\Seeder;

class TablaUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'home'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'group_work'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'accessibility'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'assessment'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'assignment_ind'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'account_circle'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'note_add'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'record_voice_over'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'input'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'list'
        ]);
        DB::table('TBL_Iconos')->insert([
            'ICO_Icono' => 'toc'
        ]);
    }
}
