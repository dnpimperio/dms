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
        Schema::create('utility_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utility_type_id');
            $table->unsignedBigInteger('room_id');
            $table->date('reading_date');
            $table->decimal('current_reading', 10, 2);
            $table->decimal('previous_reading', 10, 2)->default(0);
            $table->decimal('consumption', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by');
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('utility_type_id')->references('id')->on('utility_types')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('set null');

            // Unique constraint to prevent duplicate readings for same utility type, room, and date
            $table->unique(['utility_type_id', 'room_id', 'reading_date'], 'unique_reading_per_utility_room_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_readings');
    }
};
