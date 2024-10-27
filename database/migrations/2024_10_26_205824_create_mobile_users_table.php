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
        Schema::create('mobile_users', function (Blueprint $table) {
            $table->id('user_id'); // Llave primaria
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password'); // Contraseña para el inicio de sesión
            $table->char('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('device_id', 128)->nullable();         // ID del dispositivo móvil
            $table->string('mobile_model', 100)->nullable();      // Modelo del dispositivo móvil
            $table->string('os_version', 50)->nullable();         // Versión del sistema operativo
            $table->string('app_version', 50)->nullable();        // Versión de la aplicación
            $table->string('network_type', 20)->nullable();       // Tipo de red (Wi-Fi, 4G, etc.)
            $table->string('imei', 20)->nullable();               // IMEI del dispositivo móvil
            $table->tinyInteger('is_offline')->default(0);        // Indica si el usuario puede estar en modo offline
            $table->string('status', 50)->default('active');      // Estado del usuario (activo/inactivo)

            // Campos adicionales para el inicio de sesión
            $table->rememberToken(); // Token para recordar al usuario en la sesión
            $table->timestamp('email_verified_at')->nullable(); // Marca de verificación de email

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mobile_users', function (Blueprint $table) {
            //
        });
    }
};
