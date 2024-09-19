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
        Schema::create('contracted_municipalities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id'); // State reference (Foreign key)
            $table->integer('municipality_id'); // Municipality reference (Foreign key)
            $table->string('name'); // Name of the municipality
            $table->text('description')->nullable(); // Description of the municipality
            $table->string('url')->nullable(); // URL link of the municipality
            $table->string('token')->nullable();
            $table->date('contract_date'); // Date of the contract
            $table->string('contract_number')->nullable(); // Contract number or reference
            $table->string('contact_responsible', 100); // Responsible contact person
            $table->string('contact_position', 100)->nullable(); // Position of the responsible person
            $table->string('contact_email', 100)->nullable(); // Email of the contact person
            $table->string('contact_phone1', 15); // Phone number of the contact person
            $table->string('contact_phone2', 15)->nullable(); // Secondary phone number (optional)
            $table->boolean('status')->default(true); // Status of the contract (active or inactive)
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign keys
            $table->foreign('state_id')->references('EstadoId')->on('estado')->onDelete('cascade');
            $table->foreign(['state_id', 'municipality_id'])->references(['EstadoId', 'MunicipioId'])->on('municipio')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracted_municipalities');
    }
};
