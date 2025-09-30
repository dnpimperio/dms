<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Add new fields only if they don't exist
            if (!Schema::hasColumn('tenants', 'university')) {
                $table->string('university')->nullable()->after('occupation');
            }
            if (!Schema::hasColumn('tenants', 'course')) {
                $table->string('course')->nullable()->after('university');
            }
            
            // Add new provincial_address column only if it doesn't exist
            if (!Schema::hasColumn('tenants', 'provincial_address')) {
                $table->text('provincial_address')->nullable()->after('course');
            }
            
            // Remove civil_status column if it exists
            if (Schema::hasColumn('tenants', 'civil_status')) {
                $table->dropColumn('civil_status');
            }
        });
        
        // Copy data from permanent_address to provincial_address if permanent_address exists
        if (Schema::hasColumn('tenants', 'permanent_address')) {
            DB::statement('UPDATE tenants SET provincial_address = permanent_address WHERE provincial_address IS NULL');
            
            // Drop the old permanent_address column
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('permanent_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['university', 'course']);
            
            // Add back civil_status column
            $table->string('civil_status')->nullable();
            
            // Add back permanent_address column
            $table->text('permanent_address')->nullable();
        });
        
        // Copy data back from provincial_address to permanent_address
        DB::statement('UPDATE tenants SET permanent_address = provincial_address');
        
        // Drop the provincial_address column
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('provincial_address');
        });
    }
};
