<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaEmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Agregar Empresa
        DB::table('TBL_Empresas')->insert([
            'EMP_Nombre_Empresa' => 'INK Agencia Digital',
            'EMP_NIT_Empresa' => '900681523-6',
            'EMP_Telefono_Empresa'  => '2889617',
            'EMP_Direccion_Empresa' => 'Cra 69 # 7-95',
            'EMP_Correo_Empresa' => 'gestion@inkdigital.co',
            'EMP_Logo_Empresa' => 'LOGO INK.png',
        ]);
    }
}
