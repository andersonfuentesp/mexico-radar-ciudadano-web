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
        Schema::create('municipality_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('municipality_id'); // Foreign key to contracted_municipalities
            $table->string('api_url'); // API URL of the service
            $table->string('api_token')->nullable(); // Token for accessing the service (optional)
            $table->enum('response_format', ['JSON', 'XML', 'CSV', 'Other'])->default('JSON'); // Format of the response
            $table->string('service_name'); // Name of the web service (e.g., GetData, SubmitReport, etc.)
            $table->text('description')->nullable(); // Description of the service
            $table->boolean('status')->default(true); // Status of the service (active or inactive)
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key relation
            $table->foreign('municipality_id')->references('id')->on('contracted_municipalities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipality_services');
    }
};
