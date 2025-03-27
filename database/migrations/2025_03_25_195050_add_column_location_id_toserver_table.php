<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //

        // $sqlFilePath = database_path('sql/v2_server_countries.sql'); // Path to your SQL file
        
        // if (File::exists($sqlFilePath)) {
        //     $sql = File::get($sqlFilePath);
        //     DB::unprepared($sql); // Run the SQL file
        // }

        Schema::table('v2_server', function (Blueprint $table) {
            // $table->integer('commission_status')->nullable()->default(null)->comment('0待确认1发放中2有效3无效')->change();

            // Make location_id nullable initially
            $table->string('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('v2_server_countries')->comment('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // // Drop new table
        // Schema::dropIfExists('v2_server_countries');

        //
        Schema::table('v2_server', function (Blueprint $table) {
            
            // Drop the foreign key constraint first
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
