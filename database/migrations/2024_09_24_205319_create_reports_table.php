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
        Schema::create('reports', function (Blueprint $table) {
            $table->char('report_id', 36)->primary();
            $table->bigInteger('report_folio')->unique();
            $table->date('report_registration_date')->nullable();
            $table->dateTime('report_registration_time')->nullable();
            $table->date('report_reported_date')->nullable();
            $table->dateTime('report_reported_time')->nullable();
            $table->integer('state_id')->nullable(); // int(11)
            $table->integer('municipality_id')->nullable(); // int(11)
            //$table->unsignedBigInteger('dependency_id')->nullable(); // bigint(20) unsigned
            // $table->integer('neighborhood_id')->nullable(); // Eliminado según solicitud
            $table->char('report_type_id', 50)->nullable(); // Cambiado a varchar (char de longitud 36)
            $table->char('report_status_id', 50)->nullable(); // Cambiado a varchar (char de longitud 36)
            //$table->unsignedBigInteger('response_id')->nullable(); // bigint(20) unsigned
            $table->string('report_address', 1024)->nullable();
            $table->string('custom_report', 100)->nullable();
            $table->string('report_comment', 90)->nullable();
            $table->char('gps_location', 50)->nullable();
            $table->point('geospatial_location')->nullable(); 
            $table->date('end_date')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('end_comment', 500)->nullable();
            $table->text('end_photo')->nullable();
            $table->string('end_photo_gxi', 2048)->nullable();
            $table->string('device_id', 128)->nullable();
            $table->char('attention_user_id', 36)->nullable();
            $table->string('attention_user_nick', 100)->nullable();
            $table->char('attention_user_id_gam', 40)->nullable();
            $table->string('encrypted_attention_user', 200)->nullable();
            $table->char('blocked_report', 2)->nullable();
            $table->dateTime('block_date')->nullable();
            $table->string('block_user', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->char('phone', 20)->nullable();
            $table->text('reported_photo')->nullable();
            $table->string('reported_photo_gxi', 2048)->nullable();
            $table->char('status_sd', 10)->nullable();
            $table->char('status_list_sd', 10)->nullable();
            $table->tinyInteger('twitter_status')->nullable();
            $table->text('response_text')->nullable();

            // Nuevo campo para indicar si el reporte es de un municipio contratado
            $table->tinyInteger('is_contracted_municipality')->default(0); // 0 = No, 1 = Sí

            // Campos adicionales para reportes desde el celular
            $table->string('mobile_model', 100)->nullable();  // Modelo del dispositivo móvil
            $table->string('os_version', 50)->nullable();     // Versión del sistema operativo
            $table->string('app_version', 50)->nullable();    // Versión de la app
            $table->tinyInteger('is_offline')->nullable();    // Indica si fue enviado en modo offline
            $table->text('attachments')->nullable();          // Archivos adjuntos como fotos adicionales
            $table->string('network_type', 20)->nullable();   // Tipo de red al enviar (Wi-Fi, 4G, etc.)
            $table->string('imei', 20)->nullable();           // IMEI del dispositivo móvil
            $table->string('generated_from')->nullable();

            $table->timestamps();

            // Foreign keys (only necessary ones)
            $table->foreign('state_id', 'fk_reports_state')->references('EstadoId')->on('estado')->onDelete('cascade');
            $table->foreign(['state_id', 'municipality_id'], 'fk_reports_municipality')->references(['EstadoId', 'MunicipioId'])->on('municipio')->onDelete('cascade');
            // Eliminamos las claves foráneas de `report_type_id` y `report_status_id`
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
