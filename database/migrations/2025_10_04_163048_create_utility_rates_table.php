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
            $table->foreignId('utility_type_id')->constrained('utility_types')->onDelete('cascade');
            $table->decimal('rate_per_unit', 10, 4);
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('utility_rates');
    }
};
