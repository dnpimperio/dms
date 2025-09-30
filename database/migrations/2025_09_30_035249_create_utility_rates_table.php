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
        Schema::create('utility_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utility_type_id');
            $table->decimal('rate_per_unit', 10, 4);
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('utility_type_id')->references('id')->on('utility_types')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Index for efficient rate lookups
            $table->index(['utility_type_id', 'effective_from', 'effective_until'], 'utility_rates_lookup_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_rates');
    }
};
