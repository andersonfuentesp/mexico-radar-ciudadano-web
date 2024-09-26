<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->integer('state_id'); // Estado ID
            $table->integer('municipality_id'); // Municipio ID
            $table->integer('neighborhood_id'); // Neighborhood ID
            $table->string('neighborhood_name', 100); // Nombre del barrio
            $table->char('postal_code', 6); // Código postal
            $table->timestamps(); // Timestamps para created_at y updated_at

            // Definir clave primaria compuesta por state_id, municipality_id, y neighborhood_id
            $table->primary(['state_id', 'municipality_id', 'neighborhood_id']);

            // Índice en el nombre del barrio
            $table->index('neighborhood_name', 'UNEIGHBORHOOD');

            // Clave foránea hacia la tabla municipio
            $table->foreign(['state_id', 'municipality_id'])
                  ->references(['EstadoId', 'MunicipioId'])
                  ->on('municipio')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neighborhoods');
    }
};
