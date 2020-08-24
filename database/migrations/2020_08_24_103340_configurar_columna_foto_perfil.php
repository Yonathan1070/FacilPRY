<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfigurarColumnaFotoPerfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TBL_Usuarios', function (Blueprint $table) {
            DB::statement("ALTER TABLE TBL_Usuarios MODIFY USR_Foto_Perfil_Usuario BLOB");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TBL_Usuarios', function (Blueprint $table) {
            DB::statement("ALTER TABLE TBL_Usuarios MODIFY USR_Foto_Perfil_Usuario TEXT");
        });
    }
}
