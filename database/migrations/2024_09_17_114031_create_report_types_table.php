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
        Schema::create('report_types', function (Blueprint $table) {
            $table->id('ReportTypeId');
            $table->smallInteger('ReportTypeOrderPriority');
            $table->string('ReportTypeName', 100);
            $table->text('ReportTypePinReported')->nullable(); // Ruta de la imagen del pin reportado
            $table->text('ReportTypePinInProcess')->nullable(); // Ruta de la imagen del pin en proceso
            $table->text('ReportTypePinFinished')->nullable(); // Ruta de la imagen del pin finalizado
            $table->text('ReportTypeImage')->nullable(); // Ruta de la imagen principal
            $table->boolean('ReportTypeIsActive');
            $table->dateTime('ReportTypeRegistrationDate');
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
        Schema::dropIfExists('report_types');
    }
};
