<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = public_path('backend/estado_municipio.sql');
        DB::unprepared(file_get_contents($path));

        // Cargar el script que inserta los datos en las tablas
        $pathData = public_path('backend/estado_municipio_data.sql');
        DB::unprepared(file_get_contents($pathData));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Aquí puedes agregar lógica para revertir los cambios si es necesario.
        DB::statement('DROP TABLE IF EXISTS `MunicipioUbi`');
        DB::statement('DROP TABLE IF EXISTS `municipiopol`');
        DB::statement('DROP TABLE IF EXISTS `municipio`');
        DB::statement('DROP TABLE IF EXISTS `EstadoUbi`');
        DB::statement('DROP TABLE IF EXISTS `estadopol`');
        DB::statement('DROP TABLE IF EXISTS `estado`');
    }
};
