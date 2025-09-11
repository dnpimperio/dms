<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->enum('status', ['active', 'pending', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent double booking
            $table->unique(['room_id', 'tenant_id', 'start_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_assignments');
    }
};
