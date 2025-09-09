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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('nationality');
            $table->string('occupation');
            $table->string('civil_status');
            
            // Contact Details
            $table->string('phone_number');
            $table->string('alternative_phone')->nullable();
            $table->string('personal_email')->unique();
            $table->text('permanent_address');
            $table->text('current_address')->nullable();
            
            // ID Verification
            $table->string('id_type');
            $table->string('id_number');
            $table->string('id_image_path');
            
            // Additional Information
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('tenants');
    }
};
