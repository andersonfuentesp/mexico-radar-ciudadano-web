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
        Schema::create('mobile_user_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relación con mobile_users
            $table->integer('state_id'); // Relación con estado
            $table->integer('municipality_id'); // Relación con municipio
            $table->boolean('is_active')->default(0);
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('user_id')->on('mobile_users')->onDelete('cascade');
            $table->foreign('state_id', 'fk_mobile_user_locations_state')->references('EstadoId')->on('estado')->onDelete('cascade');
            $table->foreign(['state_id', 'municipality_id'], 'fk_mobile_user_locations_municipality')->references(['EstadoId', 'MunicipioId'])->on('municipio')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_user_locations');
    }
};
