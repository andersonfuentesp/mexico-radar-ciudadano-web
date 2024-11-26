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
        Schema::create('mobile_password_resets', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->string('email')->index(); // Correo del usuario
            $table->string('token'); // Token único
            $table->timestamp('created_at')->nullable(); // Fecha de creación
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_password_resets');
    }
};
